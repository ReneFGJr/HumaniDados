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

    public function extrairDados($idLattes)
    {
        helper(['filesystem']);

        ROOTPATH . '..\database\sample\xml\\';

        $xmlPath  = ROOTPATH . '../database/xml/' . $idLattes . '.xml';
        $zipPath  = ROOTPATH . '../database/zip/' . $idLattes . '.zip';
        $zipDir   = ROOTPATH . '../database/zip/';
        $xmlDir   = ROOTPATH . '../database/xml/';

        // Criar diretórios se não existirem
        if (!is_dir($zipDir)) mkdir($zipDir, 0777, true);
        if (!is_dir($xmlDir)) mkdir($xmlDir, 0777, true);

        // ------------------------------------------------------------------------------------
        // 1) VERIFICAR SE O XML EXISTE
        // ------------------------------------------------------------------------------------
        if (!file_exists($xmlPath)) {

            echo "📄 XML não encontrado para ID {$idLattes}. Iniciando download...<br>";

            // --------------------------------------------------------------------------------
            // 2) BAIXAR O ZIP DA API
            // --------------------------------------------------------------------------------
            $url = "https://brapci.inf.br/ws/api/?verb=lattes&q=$idLattes";
            $zipContent = @file_get_contents($url);

            if (!$zipContent) {
                echo "❌ Falha ao baixar arquivo da API BRAPCI.<br>URL: {$url}";
                return false;
            }

            file_put_contents($zipPath, $zipContent);
            echo "✅ ZIP baixado com sucesso: {$zipPath}<br>";

            // --------------------------------------------------------------------------------
            // 3) VERIFICAR E DESCOMPACTAR ZIP
            // --------------------------------------------------------------------------------
            $zip = new \ZipArchive;

            if ($zip->open($zipPath) === TRUE) {

                // Extrai todo o conteúdo para a pasta zip/
                $zip->extractTo($zipDir);
                $zip->close();
                echo "📦 ZIP descompactado com sucesso.<br>";
            } else {
                echo "❌ Erro ao abrir arquivo ZIP.<br>";
                return false;
            }

            // --------------------------------------------------------------------------------
            // 4) MOVIMENTAR O XML EXTRAÍDO PARA /dados/xml
            // --------------------------------------------------------------------------------
            $extractedXml = glob($zipDir . "*.xml");

            if (count($extractedXml) == 0) {
                echo "❌ Nenhum XML encontrado dentro do ZIP.<br>";
                return false;
            }

            // Pegamos o primeiro arquivo XML encontrado
            $foundXml = $extractedXml[0];

            // Move o arquivo
            rename($foundXml, $xmlPath);
            echo "📁 XML movido para: {$xmlPath}<br>";

            // Opcional: apagar o ZIP depois do processamento
            // unlink($zipPath);
        }

        // ------------------------------------------------------------------------------------
        // 5) PROCESSAR O XML
        // ------------------------------------------------------------------------------------
        echo "⏳ Processando pesquisador com ID Lattes: {$idLattes}...<br>";

        // Aqui você coloca a lógica para ler o XML e armazenar no banco
        // $xml = simplexml_load_file($xmlPath);

        return true;
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
        $msg .= 'Processando '.$total.' pesquisadores para verificar.<br>';
        foreach ($pesquisadores as $p) {
            $idlattes = trim($p['idlattes']);
            $arquivo = $this->fileLattesPath($idlattes);

            if (file_exists($arquivo)) {
                // Atualiza status somente se ainda não estiver coletado
                if ($p['situacao_coleta'] !== 'coletado') {
                    $this->update($p['id'], ['situacao_coleta' => 'coletado']);
                    $encontrados++;
                } else if ($p['situacao_coleta'] === 'coletado') {
                    $msg .= $this->processarXML($idlattes).'<br>';
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
        $basePath = ROOTPATH . '..\database\xml\\';
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
            exit;
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
        $producaoArtisticaCultural = $xml->{'OUTRA-PRODUCAO'}->{'PRODUCAO-ARTISTICA-CULTURAL'};

        if (!$producaoArtisticaCultural) {
            pre($producaoArtisticaCultural);
            echo "OPS";
            exit;
        }

                
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
                case 'OUTRA-PRODUCAO-ARTISTICA-CULTURAL':
                    $outraProducao = $producao;
                    //pre($outraProducao);
                    break;
                default:
                    echo "ERRO TYPE $tipo";
                    exit;
                    break;
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
