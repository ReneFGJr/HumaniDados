<?php

namespace App\Models;

use CodeIgniter\Model;

class ProceedingsModel extends Model
{
    protected $table            = 'proceedings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [

        // Identificação
        'sequencia_producao',
        'id_lattes',

        // Dados básicos
        'natureza',
        'titulo',
        'titulo_ingles',
        'ano_trabalho',
        'pais_evento',
        'idioma',
        'meio_divulgacao',
        'home_page',
        'flag_relevancia',
        'doi',
        'flag_divulgacao_cientifica',

        // Evento
        'classificacao_evento',
        'nome_evento',
        'nome_evento_ingles',
        'cidade_evento',
        'ano_realizacao',
        'titulo_anais',
        'volume',
        'fasciculo',
        'serie',
        'pagina_inicial',
        'pagina_final',
        'isbn',
        'nome_editora',
        'cidade_editora',

        // Autores
        'nome_autores',

        // Keywords
        'palavras_chave'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    function zeraDados($idlattes)
    {
        $this->where('id_lattes', $idlattes)->delete();
        return true;
    }

    function extrairProceedings($xml)
    {
        $proceedings = [];

        foreach ($xml->xpath('//TRABALHO-EM-EVENTOS') as $trab) {

            $dados    = $trab->{'DADOS-BASICOS-DO-TRABALHO'};
            $detalhe  = $trab->{'DETALHAMENTO-DO-TRABALHO'};

            // ==========================
            // Autores
            // ==========================
            $autores = [];
            $nomesAutores = [];
            $cnpqIds = [];

            foreach ($trab->AUTORES as $a) {
                $autor = [
                    'nome'    => (string) $a['NOME-COMPLETO-DO-AUTOR'],
                    'citacao' => (string) $a['NOME-PARA-CITACAO'],
                    'ordem'   => (string) $a['ORDEM-DE-AUTORIA'],
                    'id_cnpq' => (string) $a['NRO-ID-CNPQ']
                ];

                $autores[] = $autor;
                $nomesAutores[] = (string) $a['NOME-PARA-CITACAO'];
                $cnpqIds[] = (string) $a['NRO-ID-CNPQ'];
            }

            // ==========================
            // Palavras-chave
            // ==========================
            $palavras = [];

            if ($trab->{'PALAVRAS-CHAVE'}) {
                foreach ($trab->{'PALAVRAS-CHAVE'}->attributes() as $k => $v) {
                    if (trim((string)$v) !== '') {
                        $palavras[] = (string)$v;
                    }
                }
            }

            // ==========================
            // Estrutura Final
            // ==========================
            $proceedings[] = [

                'id_lattes' => (string)$xml->attributes()['NUMERO-IDENTIFICADOR'],
                'sequencia' => (string)$trab['SEQUENCIA-PRODUCAO'],

                // Dados básicos
                'natureza'              => (string)$dados['NATUREZA'],
                'titulo'                => (string)$dados['TITULO-DO-TRABALHO'],
                'titulo_ingles'         => (string)$dados['TITULO-DO-TRABALHO-INGLES'],
                'ano_trabalho'          => (string)$dados['ANO-DO-TRABALHO'],
                'pais_evento'           => (string)$dados['PAIS-DO-EVENTO'],
                'idioma'                => (string)$dados['IDIOMA'],
                'meio_divulgacao'       => (string)$dados['MEIO-DE-DIVULGACAO'],
                'homepage'              => (string)$dados['HOME-PAGE-DO-TRABALHO'],
                'doi'                   => (string)$dados['DOI'],
                'flag_relevancia'       => (string)$dados['FLAG-RELEVANCIA'],
                'flag_divulgacao'       => (string)$dados['FLAG-DIVULGACAO-CIENTIFICA'],

                // Detalhamento do evento
                'classificacao_evento'  => (string)$detalhe['CLASSIFICACAO-DO-EVENTO'],
                'nome_evento'           => (string)$detalhe['NOME-DO-EVENTO'],
                'nome_evento_ingles'    => (string)$detalhe['NOME-DO-EVENTO-INGLES'],
                'cidade_evento'         => (string)$detalhe['CIDADE-DO-EVENTO'],
                'ano_realizacao'        => (string)$detalhe['ANO-DE-REALIZACAO'],
                'titulo_anais'          => (string)$detalhe['TITULO-DOS-ANAIS-OU-PROCEEDINGS'],
                'volume'                => (string)$detalhe['VOLUME'],
                'fasciculo'             => (string)$detalhe['FASCICULO'],
                'serie'                 => (string)$detalhe['SERIE'],
                'pagina_inicial'        => (string)$detalhe['PAGINA-INICIAL'],
                'pagina_final'          => (string)$detalhe['PAGINA-FINAL'],
                'isbn'                  => (string)$detalhe['ISBN'],
                'nome_editora'          => (string)$detalhe['NOME-DA-EDITORA'],
                'cidade_editora'        => (string)$detalhe['CIDADE-DA-EDITORA'],

                // Consolidados
                'nome_autores'          => implode('; ', $nomesAutores),

                // Estruturas completas
                'autores'               => $autores,
            ];
        }

        return $proceedings;
    }
}
