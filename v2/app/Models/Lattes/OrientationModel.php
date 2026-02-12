<?php

namespace App\Models\Lattes;

use CodeIgniter\Model;

class OrientationModel extends Model
{
    protected $DBGroup = 'default';

    protected $table      = 'lattes_orientacoes';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'natureza',
        'tipo',
        'ano',
        'pais',
        'idioma',
        'titulo',
        'doi',
        'id_lattes',
        'orientado',
        'status',
        'codigo_instituicao',
        'codigo_agencia_fomento'
    ];

    protected $validationRules = [];

    function resume($idLattes)
    {
        $orientacoes = $this->where('id_lattes', $idLattes)->findAll();
        $resumo = [];
        foreach ($orientacoes as $o) {
            $key = $o['tipo'] ?? 'OUTROS';
            if (!isset($resumo[$key])) {
                $resumo[$key] = [
                    'concluidas' => 0,
                    'em_andamento' => 0,
                ];
            }
            if ($o['status'] === 'CONCLUIDA') {
                $resumo[$key]['concluidas']++;
            } elseif ($o['status'] === 'EM_ANDAMENTO') {
                $resumo[$key]['em_andamento']++;
            }
        }
        return $resumo;
    }

    /**
     * Insere orientação a partir do array vindo do Lattes
     */
    public function insertFromLattes(array $data, string $idLattes)
    {
        $insertData = [
            'natureza'            => $data['natureza'] ?? null,
            'tipo'                => $data['tipo'] ?? null,
            'ano'                 => isset($data['ano']) ? (int)$data['ano'] : null,
            'titulo'              => $data['titulo'] ?? null,
            'id_lattes'           => $idLattes,
            'pais'                => null,
            'idioma'              => null,
            'doi'                 => null,
            'status'              => $data['status'] ?? null,
            'orientado'          => $data['orientado'] ?? null,
            'codigo_instituicao'  => $data['instituicao'] ?? null,
            'codigo_agencia_fomento' => null,
        ];

        return $this->insert($insertData);
    }

    public function extractOrientacoes($xml, $idLattes): array
    {
        if (!$xml || !isset($xml->{'DADOS-GERAIS'})) {
            return [];
        }

        $result = [];
        //pre($xml);
        $xml = $xml->{'OUTRA-PRODUCAO'};

        // ==============================
        // ORIENTAÇÕES EM ANDAMENTO
        // ==============================
        if (isset($xml->{'ORIENTACOES-EM-ANDAMENTO'})) {
            foreach ($xml->{'ORIENTACOES-EM-ANDAMENTO'}->children() as $tipo => $items) {
                foreach ($items as $item) {
                    $result[] = $this->parseOrientacao($item, 'EM_ANDAMENTO', $tipo);
                }
            }
        }

        // ==============================
        // ORIENTAÇÕES CONCLUÍDAS
        // ==============================
        if (isset($xml->{'ORIENTACOES-CONCLUIDAS'})) {
            foreach ($xml->{'ORIENTACOES-CONCLUIDAS'}->children() as $tipo => $items) {
                switch($tipo) {
                    case 'ORIENTACOES-CONCLUIDAS-PARA-DOUTORADO':
                        $tipo = 'DOUTORADO';
                        break;
                    case 'ORIENTACOES-CONCLUIDAS-PARA-MESTRADO':
                        $tipo = 'MESTRADO';
                        break;
                    case 'ORIENTACOES-CONCLUIDAS-DE-APERFEICOAMENTO-ESPECIALIZACAO':
                        $tipo = 'APERFEICOAMENTO/ESPECIALIZACAO';
                        break;
                    case 'ORIENTACOES-CONCLUIDAS-DE-INICIACAO-CIENTIFICA':
                        $tipo = 'INICIACAO CIENTIFICA';
                        break;
                    case 'OUTRAS-ORIENTACOES-CONCLUIDAS':
                        $tipo = 'ORIENTACAO-DE-OUTRA-NATUREZA';
                        break;
                    case 'ORIENTACOES-CONCLUIDAS-DE-TCC':
                        $tipo = 'TCC';
                        break;
                    default:
                        $tipo = 'OUTROS '.$tipo;
                        break;
                }
                $result[] = $this->parseOrientacao($items, 'CONCLUIDA', $tipo);
            }
        }
        $this->zeraLattes($idLattes);
        foreach ($result as $orientacao) {
            $this->insertFromLattes($orientacao, $idLattes);
        }
        return $result;
    }

    function zeraLattes($idLattes)
    {
        $this->where('id_lattes', $idLattes)->delete();
    }

    /**
     * Normaliza uma orientação
     */
    private function parseOrientacao($item, string $status, string $tipo): array
    {
        switch($tipo) {
            case 'MESTRADO':
                $basico = 'DADOS-BASICOS-DE-ORIENTACOES-CONCLUIDAS-PARA-MESTRADO';
                $detalhe = 'DETALHAMENTO-DE-ORIENTACOES-CONCLUIDAS-PARA-MESTRADO';
                break;
            case 'DOUTORADO':
                $basico = 'DADOS-BASICOS-DE-ORIENTACOES-CONCLUIDAS-PARA-DOUTORADO';
                $detalhe = 'DETALHAMENTO-DE-ORIENTACOES-CONCLUIDAS-PARA-DOUTORADO';
                break;
            case 'ORIENTACAO-DE-OUTRA-NATUREZA':
                $basico = 'DADOS-BASICOS-DE-OUTRAS-ORIENTACOES-CONCLUIDAS';
                $detalhe = 'DETALHAMENTO-DE-OUTRAS-ORIENTACOES-CONCLUIDAS';
                break;
            default:
                $tipo = 'OUTROS '.$tipo;
                echo "OPS ".$tipo;
                pre($item);
                exit;
                break;
        }

        $dadosBasicos = $item->{$basico} ?? null;
        $detalhamento = $item->{$detalhe} ?? null;
        if (!$dadosBasicos || !$detalhamento) {
            echo "<h1>ERRO</h1>";
            pre($dadosBasicos);
            return [];
        }

        $dd = [
            'status'         => $status,
            'tipo'           => $tipo,
            'titulo'         => (string) ($dadosBasicos['TITULO'] ?? ''),
            'ano'            => (string) ($dadosBasicos['ANO'] ?? ''),
            'natureza'       => (string) ($dadosBasicos['NATUREZA'] ?? ''),
            'orientado'      => (string) ($detalhamento['NOME-DO-ORIENTADO'] ?? ''),
            'instituicao'    => (string) ($detalhamento['NOME-DA-INSTITUICAO'] ?? ''),
            'curso'          => (string) ($detalhamento['NOME-DO-CURSO'] ?? ''),
            'tipo_orientacao' => (string) ($detalhamento['TIPO-DE-ORIENTACAO'] ?? ''),
        ];
        return $dd;
    }
}
