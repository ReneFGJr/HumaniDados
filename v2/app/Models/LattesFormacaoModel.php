<?php

namespace App\Models;

use CodeIgniter\Model;

class LattesFormacaoModel extends Model
{
    protected $table = 'lattes_formacao';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'sequencia_formacao',
        'idlattes',

        'tipo',
        'nivel',
        'titulo_tcc',
        'orientador',

        'codigo_instituicao',
        'nome_instituicao',

        'codigo_orgao',
        'nome_orgao',

        'codigo_curso',
        'nome_curso',
        'codigo_area_curso',
        'status_curso',

        'ano_inicio',
        'ano_conclusao',

        'flag_bolsa',
        'codigo_agencia',
        'nome_agencia',

        'id_orientador',
        'codigo_curso_capes',

        'titulo_tcc_ing',
        'nome_curso_ing',

        'formacao_academica_titulacao',
        'tipo_graduacao',

        'codigo_instituicao_grad',
        'nome_instituicao_grad',

        'codigo_instituicao_outra_grad',
        'nome_instituicao_outra_grad',

        'orientador_grad'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    function le($idLattes)
    {
        $dt = $this->where('idlattes', $idLattes)->findAll();
        return $dt;
    }

    /**
     * Insere ou atualiza uma formação vinda do XML do Lattes.
     *
     * @param array $dados Array já tratado extraído do XML
     * @return array Resultado com status da operação
     */
    public function dadosFormacao(array $dadosXML)
    {
        // Os dados reais do XML estão dentro de @attributes
        $attr = $dadosXML['@attributes'] ?? [];

        // Pegamos idlattes e tipo que estão FORA do @attributes
        $idlattes = $dadosXML['idlattes'] ?? null;
        $tipo     = $dadosXML['tipo'] ?? null;

        // Garantir que não falte nada essencial
        $sequencia = $attr['SEQUENCIA-FORMACAO'] ?? null;

        if (!$idlattes || !$sequencia || !$tipo) {
            return [
                'status' => 'ignorado',
                'motivo' => 'Faltam idlattes, sequencia ou tipo.',
                'entrada' => $dadosXML
            ];
        }

        // Mapear os dados para os campos da tabela
        $dados = [
            'sequencia_formacao'            => $sequencia,
            'idlattes'                      => $idlattes,
            'tipo'                          => $tipo,

            'nivel'                         => $attr['NIVEL'] ?? null,
            'titulo_tcc'                    => $attr['TITULO-DO-TRABALHO-DE-CONCLUSAO-DE-CURSO'] ?? null,
            'orientador'                    => $attr['NOME-DO-ORIENTADOR'] ?? null,

            'codigo_instituicao'            => $attr['CODIGO-INSTITUICAO'] ?? null,
            'nome_instituicao'              => $attr['NOME-INSTITUICAO'] ?? null,

            'codigo_orgao'                  => $attr['CODIGO-ORGAO'] ?? null,
            'nome_orgao'                    => $attr['NOME-ORGAO'] ?? null,

            'codigo_curso'                  => $attr['CODIGO-CURSO'] ?? null,
            'nome_curso'                    => $attr['NOME-CURSO'] ?? null,

            'codigo_area_curso'             => $attr['CODIGO-AREA-CURSO'] ?? null,
            'status_curso'                  => $attr['STATUS-DO-CURSO'] ?? null,

            'ano_inicio'                    => $attr['ANO-DE-INICIO'] ?? null,
            'ano_conclusao'                 => $attr['ANO-DE-CONCLUSAO'] ?? null,

            'flag_bolsa'                    => $attr['FLAG-BOLSA'] ?? null,
            'codigo_agencia'                => $attr['CODIGO-AGENCIA-FINANCIADORA'] ?? null,
            'nome_agencia'                  => $attr['NOME-AGENCIA'] ?? null,

            'id_orientador'                 => $attr['NUMERO-ID-ORIENTADOR'] ?? null,
            'codigo_curso_capes'            => $attr['CODIGO-CURSO-CAPES'] ?? null,

            'titulo_tcc_ing'                => $attr['TITULO-DO-TRABALHO-DE-CONCLUSAO-DE-CURSO-INGLES'] ?? null,
            'nome_curso_ing'                => $attr['NOME-CURSO-INGLES'] ?? null,

            'formacao_academica_titulacao'  => $attr['FORMACAO-ACADEMICA-TITULACAO'] ?? null,
            'tipo_graduacao'                => $attr['TIPO-GRADUACAO'] ?? null,

            'codigo_instituicao_grad'       => $attr['CODIGO-INSTITUICAO-GRAD'] ?? null,
            'nome_instituicao_grad'         => $attr['NOME-INSTITUICAO-GRAD'] ?? null,

            'codigo_instituicao_outra_grad' => $attr['CODIGO-INSTITUICAO-OUTRA-GRAD'] ?? null,
            'nome_instituicao_outra_grad'   => $attr['NOME-INSTITUICAO-OUTRA-GRAD'] ?? null,

            'orientador_grad'               => $attr['NOME-ORIENTADOR-GRAD'] ?? null,
        ];

        // 🔍 Evita duplicação: chave única
        $where = [
            'idlattes'           => $idlattes,
            'sequencia_formacao' => $sequencia,
            'tipo'               => $tipo
        ];

        $existe = $this->where($where)->first();

        if ($existe) {
            $this->update($existe['id'], $dados);
            return [
                'status' => 'atualizado',
                'id' => $existe['id'],
                'where' => $where
            ];
        }

        // Insere novo
        $id = $this->insert($dados);

        return [
            'status' => 'inserido',
            'id' => $id,
            'where' => $where
        ];
    }
}
