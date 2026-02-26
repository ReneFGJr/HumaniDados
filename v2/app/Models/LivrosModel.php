<?php

namespace App\Models;

use CodeIgniter\Model;

class LivrosModel extends Model
{
    protected $table = 'livros';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'sequencia',
        'tipo',
        'natureza',
        'titulo',
        'ano',
        'autor_nome',
        'pais_publicacao',
        'idioma',
        'meio_divulgacao',
        'homepage',
        'doi',
        'volumes',
        'paginas',
        'isbn',
        'edicao',
        'serie',
        'cidade_editora',
        'nome_editora',
        'id_lattes'
    ];
    protected $useTimestamps = true;

    function zeraDados($idlattes)
    {
        $this->where('id_lattes', $idlattes)->delete();
        return true;
    }

    function extrairLivros($xml)
    {
        $livros = [];

        foreach ($xml->xpath('//LIVRO-PUBLICADO-OU-ORGANIZADO') as $livro) {

            $dados = $livro->{'DADOS-BASICOS-DO-LIVRO'};
            $detalhe = $livro->{'DETALHAMENTO-DO-LIVRO'};

            // Autores (pode haver vários)
            $autores = [];
            foreach ($livro->AUTORES as $a) {
                $autores[] = [
                    'nome' => (string) $a['NOME-COMPLETO-DO-AUTOR'],
                    'citacao' => (string) $a['NOME-PARA-CITACAO'],
                    'ordem' => (string) $a['ORDEM-DE-AUTORIA'],
                    'id_cnpq' => (string) $a['NRO-ID-CNPQ']
                ];
            }

            // Palavras-chave (opcional no Lattes)
            $palavras = [];
            if ($livro->{'PALAVRAS-CHAVE'}) {
                foreach ($livro->{'PALAVRAS-CHAVE'}->attributes() as $k => $v) {
                    if (trim((string)$v) !== '')
                        $palavras[] = (string)$v;
                }
            }

            $livros[] = [
                'id_lattes'        => (string)$xml->attributes()['NUMERO-IDENTIFICADOR'],
                'sequencia'       => (string)$livro['SEQUENCIA-PRODUCAO'],
                'tipo'            => (string)$dados['TIPO'],
                'natureza'        => (string)$dados['NATUREZA'],
                'titulo'          => (string)$dados['TITULO-DO-LIVRO'],
                'ano'             => (string)$dados['ANO'],
                'pais_publicacao' => (string)$dados['PAIS-DE-PUBLICACAO'],
                'idioma'          => (string)$dados['IDIOMA'],
                'meio_divulgacao' => (string)$dados['MEIO-DE-DIVULGACAO'],
                'homepage'        => (string)$dados['HOME-PAGE-DO-TRABALHO'],
                'doi'             => (string)$dados['DOI'],
                'autor_nome'      => implode(', ', array_column($autores, 'nome')),

                // Detalhamento
                'volumes'         => (string)$detalhe['NUMERO-DE-VOLUMES'],
                'paginas'         => (string)$detalhe['NUMERO-DE-PAGINAS'],
                'isbn'            => (string)$detalhe['ISBN'],
                'edicao'          => (string)$detalhe['NUMERO-DA-EDICAO-REVISAO'],
                'serie'           => (string)$detalhe['NUMERO-DA-SERIE'],
                'cidade_editora'  => (string)$detalhe['CIDADE-DA-EDITORA'],
                'nome_editora'    => (string)$detalhe['NOME-DA-EDITORA'],

                // Estruturas compostas
                'autores'         => $autores,
                'palavras_chave'  => $palavras,
            ];
        }
        return $livros;
    }
}
