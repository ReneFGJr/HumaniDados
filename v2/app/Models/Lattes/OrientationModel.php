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
        'codigo_instituicao',
        'codigo_agencia_fomento'
    ];

    protected $validationRules = [];

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
                    case 'ORIENTACOES-CONCLUIDAS-DE-DOUTORADO':
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
                    case 'ORIENTACOES-CONCLUIDAS-DE-TCC':
                        $tipo = 'TCC';
                        break;
                    default:
                        $tipo = 'OUTROS '.$tipo;
                        break;
                }                
                foreach ($items as $item) {
                    $result[] = $this->parseOrientacao($items, 'CONCLUIDA', $tipo);
                }
                pre($items, false);
            }
        }

        pre($result);

        return $result;
    }

    /**
     * Normaliza uma orientação
     */
    private function parseOrientacao($item, string $status, string $tipo): array
    {        
        $basico = 'DADOS-BASICOS-DE-ORIENTACOES-CONCLUIDAS-PARA-MESTRADO';
        $detalhe = 'DETALHAMENTO-DE-ORIENTACOES-CONCLUIDAS-PARA-MESTRADO';
        echo '<h1>'.$tipo.'</h1>';
        switch($tipo) {
            case 'MESTRADO':
                $basico = 'DADOS-BASICOS-DE-ORIENTACOES-CONCLUIDAS-PARA-MESTRADO';
                $detalhe = 'DETALHAMENTO-DE-ORIENTACOES-CONCLUIDAS-PARA-MESTRADO';
                break;
            default:
                $tipo = 'OUTROS '.$tipo;
                echo "OPS ".$tipo;
                pre($item);
                break;
        }

        $dadosBasicos = $item->{$basico} ?? null;
        $detalhamento = $item->{$detalhe} ?? null;
        if (!$dadosBasicos || !$detalhamento) {
            echo "ERRO";
            pre($dadosBasicos);
            return [];
        }

        return [
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
    }
}
