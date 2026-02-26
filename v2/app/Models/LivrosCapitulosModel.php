<?php

namespace App\Models;

use CodeIgniter\Model;

class LivrosCapitulosModel extends Model
{
    protected $table = 'capitulos';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'sequencia',
        'tipo',
        'titulo_capitulo',
        'ano',
        'pais_publicacao',
        'idioma',
        'autor_nome',
        'meio_divulgacao',
        'homepage',
        'doi',
        'titulo_livro',
        'volumes',
        'pagina_inicial',
        'pagina_final',
        'isbn',
        'organizadores',
        'edicao',
        'serie',
        'cidade_editora',
        'nome_editora',
        'id_lattes'
    ];
    protected $useTimestamps = true;

    function zeraDados($idlattes)
    {
        $this->where('id_lattes',$idlattes)->delete();
        return true;
    }

    function extrairCapitulos($xml,$id_lattes)
    {
        $capitulos = [];

        foreach ($xml->xpath('//CAPITULO-DE-LIVRO-PUBLICADO') as $cap) {

            $dados = $cap->{'DADOS-BASICOS-DO-CAPITULO'};
            $detalhe = $cap->{'DETALHAMENTO-DO-CAPITULO'};

            // Autores
            $autores = [];
            foreach ($cap->AUTORES as $a) {
                $autores[] = [
                    'nome' => (string)$a['NOME-COMPLETO-DO-AUTOR'],
                    'citacao' => (string)$a['NOME-PARA-CITACAO'],
                    'ordem' => (string)$a['ORDEM-DE-AUTORIA'],
                    'id_cnpq' => (string)$a['NRO-ID-CNPQ']
                ];
            }

            $capitulos[] = [
                'id_lattes'        => (string)$id_lattes,
                'sequencia'       => (string)$cap['SEQUENCIA-PRODUCAO'],
                'tipo'            => (string)$dados['TIPO'],
                'titulo_capitulo' => (string)$dados['TITULO-DO-CAPITULO-DO-LIVRO'],
                'ano'             => (string)$dados['ANO'],
                'pais_publicacao' => (string)$dados['PAIS-DE-PUBLICACAO'],
                'idioma'          => (string)$dados['IDIOMA'],
                'meio_divulgacao' => (string)$dados['MEIO-DE-DIVULGACAO'],
                'homepage'        => (string)$dados['HOME-PAGE-DO-TRABALHO'],
                'doi'             => (string)$dados['DOI'],
                'autor_nome'      => implode(', ', array_column($autores, 'nome')),

                // Detalhamento
                'titulo_livro'    => (string)$detalhe['TITULO-DO-LIVRO'],
                'volumes'         => (string)$detalhe['NUMERO-DE-VOLUMES'],
                'pagina_inicial'  => (string)$detalhe['PAGINA-INICIAL'],
                'pagina_final'    => (string)$detalhe['PAGINA-FINAL'],
                'isbn'            => (string)$detalhe['ISBN'],
                'organizadores'   => (string)$detalhe['ORGANIZADORES'],
                'edicao'          => (string)$detalhe['NUMERO-DA-EDICAO-REVISAO'],
                'serie'           => (string)$detalhe['NUMERO-DA-SERIE'],
                'cidade_editora'  => (string)$detalhe['CIDADE-DA-EDITORA'],
                'nome_editora'    => (string)$detalhe['NOME-DA-EDITORA'],

                // Autores
                'autores'         => $autores
            ];
        }

        return $capitulos;
    }
}
