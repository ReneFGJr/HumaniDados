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
        $LattesFormacaoModel = new LattesFormacaoModel();

        $dt = $this->where('id', $id)->first();
        $dt['instituição'] = $InstituicaoLattesModel->where('id', $dt['vinculo_instituicao'])->first();
        $dt['formacao'] = $LattesFormacaoModel->le($dt['idlattes']);
        return $dt;
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
        $pesquisadores = $this->findAll();

        $total = count($pesquisadores);
        $encontrados = 0;
        $naoEncontrados = 0;
        foreach ($pesquisadores as $p) {
            $idlattes = trim($p['idlattes']);
            $arquivo = $this->fileLattesPath($idlattes);

            if (file_exists($arquivo)) {

                // Atualiza status somente se ainda não estiver coletado
                if ($p['situacao_coleta'] !== 'coletado') {
                    $this->update($p['id'], ['situacao_coleta' => 'coletado']);
                    $encontrados++;
                } else if ($p['situacao_coleta'] === 'coletado') {
                    $this->processarXML($idlattes);
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
        $basePath = ROOTPATH . '..\database\sample\xml\\';
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

        // === Extração de dados principais ===
        $nomeCompleto = (string) $xml->{'DADOS-GERAIS'}['NOME-COMPLETO'];
        $nacionalidade  = (string) $xml->{'DADOS-GERAIS'}['PAIS-DE-NACIONALIDADE'];
        $origem = (string) $xml->{'DADOS-GERAIS'}['PAIS-DE-NASCIMENTO'];
        $cidade = (string) $xml->{'DADOS-GERAIS'}['CIDADE-NASCIMENTO'];
        $orcID = (string) $xml->{'DADOS-GERAIS'}['ORCID-ID'];
        $dtUpdate = brtod((string) $xml['DATA-ATUALIZACAO']);

        echo '<h1>' . $nomeCompleto . '</h1>';
        echo '<h5>' . $nacionalidade . ' - ' . $origem . ' - ' . $cidade . '</h5>';
        echo '<h6>' . $dtUpdate . '</h6>';

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

        if (!$producaoArtisticaCultural)
            {
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
                    pre($producao);
                    $ProducaoXML->dadosBasicos($idlattes, $producao, $n, $na);
                    break;
                case 'MUSICA':
                    $n = '-DE-MUSICA';
                    $na = 'MUSICA';
                    $ProducaoXML->dadosBasicos($idlattes, $producao, $n, $na);
                    break;
                case 'OUTRA-PRODUCAO-ARTISTICA-CULTURAL':
                    $outraProducao = $producao;
                    //pre($outraProducao);
                    break;
                default:
                    pre($tipo);
                    break;
            }

        }


        /********************************* Instituição */
        $endereco = $xml->{'DADOS-GERAIS'}->{'ENDERECO'};
        $endProfissional = (array) $endereco->{'ENDERECO-PROFISSIONAL'};
        $endProfissional = $endProfissional['@attributes'];
        $instituicao = $InstituicaoLattesModel->checkInstituicao($endProfissional);
        //pre($xml);

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

        return "✅ Pesquisador {$nomeCompleto} ({$idlattes}) processado com sucesso.";
    }
}
