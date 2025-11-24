<?php

namespace App\Models;

use CodeIgniter\Model;

class LattesResearcherModel extends Model
{
    protected $table = 'lattes_researchers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'idlattes',
        'nome_completo',
        'nacionalidade',
        'ano_doutorado',
        'ano_posdoutorado',
        'ano_mestrado',
        'ano_graduacao',
        'data_atualizacao',
        'situacao_coleta',
        'nascimento_pais',
        'vinculo_instituicao',
        'orcID',
        'nascimento_cidade'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    function le($id)
    {
        $InstituicaoLattesModel = new InstituicaoLattesModel();
        $ProducaoArtisticaModel = new ProducaoArtisticaModel();
        $LattesFormacaoModel = new LattesFormacaoModel();

        $dt = $this->where('id', $id)->first();
        $dt['instituição'] = $InstituicaoLattesModel->where('id', $dt['vinculo_instituicao'])->first();
        $dt['formacao'] = $LattesFormacaoModel->le($dt['idlattes']);
        $dt['producao_artistica'] = $ProducaoArtisticaModel->resume($dt['idlattes']);
        return $dt;
    }

    public function universidadesVinculadas()
    {
        $dt = $this
            ->select('count(*) as total, instituicoes_lattes.nome_instituicao_empresa as name')
            ->join('instituicoes_lattes', 'lattes_researchers.vinculo_instituicao = instituicoes_lattes.id')
            ->like('nome_instituicao_empresa', '%universidade%')
            ->groupBy('vinculo_instituicao')
            ->findAll();
        return $dt;

    }

    private function xmlToTree($xml, $level = 0)
    {
        if (!$xml) return "";

        $html = "";
        $tagName = $xml->getName();

        // Atributos da tag
        $attrStr = "";
        foreach ($xml->attributes() as $attr => $value) {
            $attrStr .= " <span class='attr'>$attr</span>=<span class='value'>\"$value\"</span>";
        }

        // Nó inicial
        $html .= "<div class='node'>";

        // Se possui filhos → torna colapsável
        if ($xml->children()->count() > 0) {
            $html .= "<span class='caret tag'>&lt;$tagName$attrStr&gt;</span>";
            $html .= "<div class='nested hidden'>";

            // Conteúdo interno (recursivo)
            foreach ($xml->children() as $child) {
                $html .= $this->xmlToTree($child, $level + 1);
            }

            $html .= "</div>";
            $html .= "<span class='tag'>&lt;/$tagName&gt;</span>";
        } else {
            // Nó sem filhos → tag única
            $value = trim((string)$xml);
            $value = htmlspecialchars($value);

            $html .= "<span class='tag'>&lt;$tagName$attrStr&gt;</span>";

            if ($value !== "") {
                $html .= " <span class='value'>$value</span>";
            }

            $html .= "<span class='tag'>&lt;/$tagName&gt;</span>";
        }

        $html .= "</div>";

        return $html;
    }


    public function xml_content($id)
    {
        $dt = $this->where('id', $id)->first();
        if (!$dt) {
            echo "Pesquisador não encontrado para ID Lattes: $id";
            exit;
            return null;
        }
        $idlattes = $dt['idlattes'];
        $xmlPath  = $this->fileLattesPath($idlattes);

        if (!file_exists($xmlPath)) {
            echo "❌ Arquivo XML não encontrado. $xmlPath";
            return null;
        }
        /*
        $xmlContent = file_get_contents($xmlPath);
        $xmlFormatted = htmlspecialchars($xmlContent);
        $xml = simplexml_load_string($xmlContent);
        */

        $xml = simplexml_load_file($xmlPath);
        $xmlFormatted = $this->xmlToTree($xml);



        return $xmlFormatted;
    }

    public function harvestDados()
    {
        helper(['filesystem']);

        // Libera o buffer para mostrar em tempo real
        @ini_set('output_buffering', 'off');
        @ini_set('zlib.output_compression', false);
        while (ob_get_level() > 0) {
            ob_end_flush();
        }
        ob_implicit_flush(true);

        $pesquisadores = $this->findAll();
        $total = count($pesquisadores);
        $processados = 0;

        $msg = "🚀 Iniciando processo de harvesting<br>";
        $msg .=  "🔍 Total de pesquisadores: {$total}<br><br>";
        echo '<script>';
        echo 'document.getElementById("output").innerHTML = "' . $msg . '";';
        echo '</script>';
        flush();
        sleep(1);

        foreach ($pesquisadores as $p) {
            $msg = '';
            $idlattes = trim($p['idlattes']);

            $msg .= "➡️ Processando ID: <b>{$idlattes}</b> ... ";

            $msg .= $sucesso = $this->extrairDados($idlattes);

            if ($sucesso) {
                $processados++;
                $msg .= "<span style='color:green'>OK</span><br>";
                sleep(2);
            } else {
                $msg .= "<span style='color:red'>já coletado</span><br>";
                sleep(0.5);
            }

            $msg .= "<br>🏁 <b>Harvesting concluído</b><br>";
            $msg .= "📌 Total: {$total}<br>";
            $msg .= "✅ Sucesso: {$processados}<br>";

            echo '<script>';
            echo 'document.getElementById("output").innerHTML = "' . $msg . '";';
            echo '</script>';
            flush();

        }
        flush();
    }


    public function extrairDados($idLattes)
    {
        helper(['filesystem']);

        ROOTPATH . '..\database\sample\xml\\';

        $xmlPath  = $this->fileLattesPath($idLattes);
        $zipPath  = str_replace('xml', 'zip', $xmlPath);
        $zipDir   = substr($zipPath, 0, strpos($zipPath, 'zip')) . 'zip/';
        $xmlDir   = substr($xmlPath, 0, strpos($xmlPath, 'xml')) . 'xml/';

        // Criar diretórios se não existirem
        if (!is_dir($zipDir)) mkdir($zipDir, 0777, true);
        if (!is_dir($xmlDir)) mkdir($xmlDir, 0777, true);

        $msg = '';

        // ------------------------------------------------------------------------------------
        // 1) VERIFICAR SE O XML EXISTE
        // ------------------------------------------------------------------------------------
        if (!file_exists($xmlPath)) {

            $msg .= "<br>📄 XML não encontrado para ID {$idLattes}. Iniciando download...<br>";

            // --------------------------------------------------------------------------------
            // 2) BAIXAR O ZIP DA API
            // --------------------------------------------------------------------------------
            $url = "https://brapci.inf.br/ws/api/?verb=lattes&q=$idLattes";
            $zipContent = @file_get_contents($url);

            if (!$zipContent) {
                return "❌ Falha ao baixar arquivo da API BRAPCI.<br>URL: {$url}";
            }

            file_put_contents($zipPath, $zipContent);
            $msg .= "✅ ZIP baixado com sucesso: {$zipPath}<br>";

            // --------------------------------------------------------------------------------
            // 3) VERIFICAR E DESCOMPACTAR ZIP
            // --------------------------------------------------------------------------------
            $zip = new \ZipArchive;

            if ($zip->open($zipPath) === TRUE) {

                // Extrai todo o conteúdo para a pasta zip/
                $zip->extractTo($zipDir);
                $zip->close();
                $msg .= "📦 ZIP descompactado com sucesso.<br>";
            } else {
                $msg .= "❌ Erro ao abrir arquivo ZIP.<br>";
                return $msg;
            }

            // --------------------------------------------------------------------------------
            // 4) MOVIMENTAR O XML EXTRAÍDO PARA /dados/xml
            // --------------------------------------------------------------------------------
            $extractedXml = glob($zipDir . "*.xml");

            if (count($extractedXml) == 0) {
                $msg .= "❌ Nenhum XML encontrado dentro do ZIP.<br>";
                return $msg;
            }

            // Pegamos o primeiro arquivo XML encontrado
            $foundXml = $extractedXml[0];

            // Move o arquivo
            rename($foundXml, $xmlPath);
            $msg .= "📁 XML movido para: {$xmlPath}<br>";

            // Opcional: apagar o ZIP depois do processamento
            // unlink($zipPath);
        }
        return $msg;
    }


    function validarIDLattes(string $code): bool
    {
        $dig = substr($code, 15, 1);
        $code = substr($code, 0, 15);

        $weightflag = true;
        $sum = 0;
        for ($i = strlen($code) - 1; $i >= 0; $i--) {
            $sum += (int)$code[$i] * ($weightflag ? 3 : 1);
            $weightflag = !$weightflag;
        }
        $ver = (10 - ($sum % 10)) % 10;
        if ($ver == $dig) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 🔍 Verifica arquivos XML na pasta e atualiza situacao_coleta
     * Caminho relativo: ../data/sample/xml/
     */
    public function verificarArquivos()
    {
        $msg = '';
        $pesquisadores = $this->findAll();

        $total = count($pesquisadores);
        $encontrados = 0;
        $naoEncontrados = 0;
        $msg .= 'Processando ' . $total . ' pesquisadores para verificar.<br>';
        foreach ($pesquisadores as $p) {
            $idlattes = trim($p['idlattes']);
            $arquivo = $this->fileLattesPath($idlattes);

            if (file_exists($arquivo)) {
                // Atualiza status somente se ainda não estiver coletado
                if ($p['situacao_coleta'] !== 'coletado') {
                    $this->update($p['id'], ['situacao_coleta' => 'coletado']);
                    $encontrados++;
                } else if ($p['situacao_coleta'] === 'coletado') {
                    $msg .= $this->processarXML($idlattes) . '<br>';
                    $encontrados++;
                }
            } else {
                $naoEncontrados++;
            }
        }

        $msg = "Verificação concluída.<br>
        🔹 Total: {$total}<br>
        ✅ Encontrados e atualizados: {$encontrados}<br>
        ⚠️ Não encontrados: {$naoEncontrados}";
        return $msg;
    }

    function fileLattesPath($idlattes)
    {
        $dir = ROOTPATH;
        $dir = substr($dir,0,strpos($dir,'v2'));
        $dir = str_replace('\\','/',$dir);
        $basePath = $dir . 'database/xml/';
        return $basePath . $idlattes . '.xml';
    }

    public function processarXML($idlattes)
    {
        $InstituicaoLattesModel = new InstituicaoLattesModel();
        $LattesFormacaoModel = new LattesFormacaoModel();
        $ProducaoArtisticaModel = new ProducaoArtisticaModel();
        $arquivo = $this->fileLattesPath($idlattes);
        $ProducaoXML = new ProducaoXML();

        if (!file_exists($arquivo)) {
            echo "❌ Arquivo XML não encontrado para ID Lattes: {$idlattes}";
            return "";
        }
        // Carregar XML com tratamento de erros
        libxml_use_internal_errors(true);
        $xml = simplexml_load_file($arquivo);

        if (!$xml) {
            echo "❌ Erro ao ler o XML do pesquisador {$idlattes}";
            exit;
        }


        /********************* Zerar */
        $ProducaoXML->zeraDados($idlattes);


        // === Extração de dados principais ===
        $nomeCompleto = (string) $xml->{'DADOS-GERAIS'}['NOME-COMPLETO'];
        $nacionalidade  = (string) $xml->{'DADOS-GERAIS'}['PAIS-DE-NACIONALIDADE'];
        $origem = (string) $xml->{'DADOS-GERAIS'}['PAIS-DE-NASCIMENTO'];
        $cidade = (string) $xml->{'DADOS-GERAIS'}['CIDADE-NASCIMENTO'];
        $orcID = (string) $xml->{'DADOS-GERAIS'}['ORCID-ID'];
        $dtUpdate = brtod((string) $xml['DATA-ATUALIZACAO']);

        // Inicializa variáveis
        $anoGraduacao = $anoMestrado = $anoDoutorado = $anoPosDoc = null;

        $formacaoTodos = $xml->{'DADOS-GERAIS'}->{'FORMACAO-ACADEMICA-TITULACAO'};
        // === Extração dos anos de formação ===
        if (isset($formacaoTodos)) {

            foreach ($formacaoTodos as $nivel) {
                foreach ($nivel->children() as $formacao) {

                    $tipo = $formacao->getName();
                    $ano  = (string) $formacao['ANO-DE-CONCLUSAO'];

                    $formacaoDT = (array) $formacao;
                    $formacaoDT['idlattes'] = $idlattes;
                    $formacaoDT['tipo'] = $tipo;

                    $LattesFormacaoModel->dadosFormacao($formacaoDT);

                    switch ($tipo) {
                        case 'GRADUACAO':
                            $anoGraduacao = $ano;
                            break;
                        case 'MESTRADO':
                            $anoMestrado = $ano;
                            break;
                        case 'DOUTORADO':
                            $anoDoutorado = $ano;
                            break;
                        case 'POS_DOUTORADO':
                            $anoPosDoc = $ano;
                            break;
                    }
                }
            }
        }

        /***************** PRODUCAO-BIBLIOGRAFICA */
        /******************** TRABALHOS-EM-EVENTOS */
        /******************** ARTIGOS-PUBLICADOS */
        /******************** LIVROS-E-CAPITULOS */
        /******************** PATENTES */
        /******************** DEMAIS-TIPOS-DE-PRODUCAO-BIBLIOGRAFICA */

        /***************** PRODUCAO-TECNICA */
        /******************** TRABALHOS-TECNICOS */
        /******************** DEMAIS-TIPOS-DE-PRODUCAO-TECNICA */
        /******************** DESENVOLVIMENTO-DE-MATERIAL-DIDATICO-OU-INSTRUCIONAL */
        /*************** OUTRA-PRODUCAO */
        /***************** PRODUCAO-ARTISTICA-CULTURAL */
        /******************** ARTES-VISUAIS */
        //pre($xml);
        $outraProducao = $xml->{'OUTRA-PRODUCAO'} ?? null;
        if ($outraProducao && isset($outraProducao->{'PRODUCAO-ARTISTICA-CULTURAL'})) {
            $producaoArtisticaCultural = $xml->{'OUTRA-PRODUCAO'}->{'PRODUCAO-ARTISTICA-CULTURAL'};

            if ($producaoArtisticaCultural->children()->count() > 0) {
                foreach ($producaoArtisticaCultural->children() as $producao) {
                    $tipo = $producao->getName();
                    switch ($tipo) {
                        case 'ARTES-VISUAIS':
                            $n = '-DE-ARTES-VISUAIS';
                            $na = 'ARTES-VISUAIS';
                            $ProducaoXML->dadosBasicos($idlattes, $producao, $n, $na);
                            break;
                        case 'ARTES-CENICAS':
                            $n = '-DE-ARTES-CENICAS';
                            $na = 'ARTES-CENICAS';
                            $ProducaoXML->dadosBasicos($idlattes, $producao, $n, $na);
                            break;
                        case 'MUSICA':
                            $n = '-DA-MUSICA';
                            $na = 'MUSICA';
                            $ProducaoXML->dadosBasicos($idlattes, $producao, $n, $na);
                            break;
                        case 'APRESENTACAO-EM-RADIO-OU-TV':
                            $n = '-EM-RADIO-OU-TV';
                            $na = 'OUTRO';
                            $ProducaoXML->dadosBasicos($idlattes, $producao, $n, $na);
                            break;
                        case 'OUTRA-PRODUCAO-ARTISTICA-CULTURAL':
                            $n = '-DE-OUTRA-PRODUCAO-ARTISTICA-CULTURAL';
                            $na = 'OUTROS';
                            $ProducaoXML->dadosBasicos($idlattes, $producao, $n, $na);
                            break;
                        case 'ARRANJO-MUSICAL':
                            $n = '-ARRANJO-MUSICAL';
                            $na = 'MUSICA';
                            $ProducaoXML->dadosBasicos($idlattes, $producao, $n, $na);
                            break;
                        case 'SONOPLASTIA':
                            $n = '-SONOPLASTIA';
                            $na = 'MUSICA';
                            $ProducaoXML->dadosBasicos($idlattes, $producao, $n, $na);
                            break;
                        case 'CURSO-DE-CURTA-DURACAO':
                            $n = '-CURSO-DE-CURTA-DURACAO';
                            $na = 'OUTROS';
                            $ProducaoXML->dadosBasicos($idlattes, $producao, $n, $na);
                            //pre($outraProducao);
                            break;
                        case 'OBRA-DE-ARTES-VISUAIS':
                            $n = '-OBRA-DE-ARTES-VISUAIS';
                            $na = 'ARTES-VISUAIS';
                            $ProducaoXML->dadosBasicos($idlattes, $producao, $n, $na);
                            break;

                        /*
                        case 'OBRA-DE-ARTES-VISUAIS':
                            $n = '-DE-ARTES-VISUAIS';
                            $na = 'ARTES-VISUAIS';
                            $ProducaoXML->dadosBasicos($idlattes, $producao, $n, $na);

                            break;
                        case 'COMPOSICAO-MUSICAL':
                            break;
                        case 'APRESENTACAO-DE-OBRA-ARTISTICA':
                            break;
                        case 'OUTRA-PRODUCAO-ARTISTICA-CULTURAL':
                            $outraProducao = $producao;
                            //pre($outraProducao);
                            break;
                        */
                        default:
                            echo "ERRO TYPE $tipo em idlattes $idlattes";
                            exit;
                            break;
                    }
                }
            }
        }

        /********************************* Instituição */
        $endereco = $xml->{'DADOS-GERAIS'}->{'ENDERECO'};
        $endProfissional = (array) $endereco->{'ENDERECO-PROFISSIONAL'};
        $endProfissional = $endProfissional['@attributes'];
        $instituicao = $InstituicaoLattesModel->checkInstituicao($endProfissional);

        $InstituicaoLattesModel->checkInstituicao($endProfissional);

        // === Atualização no banco ===
        $dados = [
            'nome_completo'     => $nomeCompleto,
            'nacionalidade'     => $nacionalidade,
            'ano_graduacao'     => $anoGraduacao,
            'ano_mestrado'      => $anoMestrado,
            'ano_doutorado'     => $anoDoutorado,
            'ano_posdoutorado'  => $anoPosDoc,
            'nascimento_pais'   => $origem,
            'nascimento_cidade' => $cidade,
            'orcID'             => $orcID,
            'data_atualizacao'  => $dtUpdate,
            'vinculo_instituicao' => $instituicao,
            'situacao_coleta'   => 'processado'
        ];
        $this->where('idlattes', $idlattes)->set($dados)->update();
        $msg = "✅ Pesquisador {$nomeCompleto} ({$idlattes}) processado com sucesso.<br>";
        return $msg;
    }
}
