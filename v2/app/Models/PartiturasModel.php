<?php

namespace App\Models;

use CodeIgniter\Model;

class PartiturasModel extends Model
{
    protected $table            = 'partitura_musical';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'id_lattes',
        'sequencia_producao',
        'natureza',
        'titulo',
        'titulo_ingles',
        'autor_nome',
        'total_autores',
        'ano',
        'pais_publicacao',
        'idioma',
        'meio_divulgacao',
        'homepage',
        'flag_relevancia',
        'doi',
        'formacao_instrumental',
        'editora',
        'cidade_editora',
        'numero_paginas',
        'numero_catalogo',
        'descricao_adicional',
        'descricao_adicional_ingles',
        'created_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $dateFormat    = 'datetime';

    protected $validationRules = [
        'titulo'      => 'required|min_length[3]',
        'autor_nome'  => 'required|min_length[3]',
        'ano'         => 'permit_empty|numeric|exact_length[4]',
        'doi'         => 'permit_empty|valid_url'
    ];

    protected $validationMessages = [
        'titulo' => [
            'required' => 'O título é obrigatório.'
        ],
        'autor_nome' => [
            'required' => 'O autor é obrigatório.'
        ]
    ];

    protected $skipValidation = false;

    /*
    |--------------------------------------------------------------------------
    | Métodos auxiliares
    |--------------------------------------------------------------------------
    */

    /**
     * Busca geral por título, autor ou ano
     */
    public function search($term)
    {
        return $this->groupStart()
            ->like('titulo', $term)
            ->orLike('titulo_ingles', $term)
            ->orLike('autor_nome', $term)
            ->orLike('ano', $term)
            ->groupEnd()
            ->orderBy('ano', 'DESC')
            ->findAll();
    }

    /**
     * Retorna partitura por DOI
     */
    public function getByDoi($doi)
    {
        return $this->where('doi', $doi)->first();
    }

    /**
     * Retorna partitura por ano
     */
    public function getByYear($ano)
    {
        return $this->where('ano', $ano)
            ->orderBy('titulo', 'ASC')
            ->findAll();
    }

    /**
     * Retorna estatística por ano
     */
    public function countByYear()
    {
        return $this->select('ano, COUNT(*) as total')
            ->groupBy('ano')
            ->orderBy('ano', 'DESC')
            ->findAll();
    }

    function zeraDados($idlattes)
    {
        $this->where('id_lattes', $idlattes)->delete();
        return true;
    }

    function extrairPartituras($xml)
    {
        $partituras = [];

        foreach ($xml->xpath('//PARTITURA-MUSICAL') as $partitura) {

            $dados    = $partitura->{'DADOS-BASICOS-DA-PARTITURA'};
            $detalhe  = $partitura->{'DETALHAMENTO-DA-PARTITURA'};

            // ==========================
            // Autores
            // ==========================
            $autores = [];

            foreach ($partitura->AUTORES as $a) {
                $autores[] = [
                    'nome'     => (string) $a['NOME-COMPLETO-DO-AUTOR'],
                    'citacao'  => (string) $a['NOME-PARA-CITACAO'],
                    'ordem'    => (int) $a['ORDEM-DE-AUTORIA'],
                    'id_cnpq'  => (string) $a['NRO-ID-CNPQ']
                ];
            }

            $autorString = implode('; ', array_column($autores, 'nome'));
            $totalAutores = count($autores);

            // ==========================
            // Montagem do array final
            // ==========================
            $partituras[] = [
                'id_lattes'          => (string) $xml->attributes()['NUMERO-IDENTIFICADOR'],
                'sequencia_producao' => (int) $partitura['SEQUENCIA-PRODUCAO'],

                // Dados básicos
                'natureza'           => (string) $dados['NATUREZA'],
                'titulo'             => (string) $dados['TITULO'],
                'titulo_ingles'      => (string) $dados['TITULO-INGLES'],
                'ano'                => (int) $dados['ANO'],
                'pais_publicacao'    => (string) $dados['PAIS-DE-PUBLICACAO'],
                'idioma'             => (string) $dados['IDIOMA'],
                'meio_divulgacao'    => (string) $dados['MEIO-DE-DIVULGACAO'],
                'homepage'           => (string) $dados['HOME-PAGE-DO-TRABALHO'],
                'doi'                => (string) $dados['DOI'],
                'flag_relevancia'    => (string) $dados['FLAG-RELEVANCIA'],

                // Autores
                'autor_nome'         => $autorString,
                'total_autores'      => $totalAutores,
                'autores'            => $autores,

                // Detalhamento
                'formacao_instrumental' => (string) $detalhe['FORMACAO-INSTRUMENTAL'],
                'editora'               => (string) $detalhe['EDITORA'],
                'cidade_editora'        => (string) $detalhe['CIDADE-DA-EDITORA'],
                'numero_paginas'        => (int) $detalhe['NUMERO-DE-PAGINAS'],
                'numero_catalogo'       => (string) $detalhe['NUMERO-DO-CATALOGO'],

                // Informações adicionais
                'descricao_adicional'         => (string) $partitura->{'INFORMACOES-ADICIONAIS'}['DESCRICAO-INFORMACOES-ADICIONAIS'],
                'descricao_adicional_ingles'  => (string) $partitura->{'INFORMACOES-ADICIONAIS'}['DESCRICAO-INFORMACOES-ADICIONAIS-INGLES'],
            ];
        }

        return $partituras;
    }
}
