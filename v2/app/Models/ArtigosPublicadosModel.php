<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtigosPublicadosModel extends Model
{
    
    protected $table      = 'artigos_publicados';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'sequencia',
        'id_lattes',
        'natureza',
        'titulo',
        'ano',
        'idioma',
        'meio_divulgacao',
        'homepage',
        'doi',
        'periodico',
        'issn',
        'volume',
        'serie',
        'pagina_inicial',
        'pagina_final',
        'autor_nome',
        'autor_citacao',
        'autor_id_cnpq'
    ];

    protected $useTimestamps = true;

    function zeraDados($idlattes)
    {        
        $this->where('id_lattes',$idlattes)->delete();
        return true;
    }

function extrairArtigos($xml)
{
    $artigos = [];

    foreach ($xml->xpath('//ARTIGO-PUBLICADO') as $artigo) {

        $dados = $artigo->{'DADOS-BASICOS-DO-ARTIGO'};
        $detalhe = $artigo->{'DETALHAMENTO-DO-ARTIGO'};
        $autor = $artigo->AUTORES;

        $artigos[] = [
            'id_lattes'        => (string)$xml['NUMERO-IDENTIFICADOR'],
            'sequencia'         => (string)$artigo['SEQUENCIA-PRODUCAO'],
            'natureza'          => (string)$dados['NATUREZA'],
            'titulo'            => (string)$dados['TITULO-DO-ARTIGO'],
            'ano'               => (string)$dados['ANO-DO-ARTIGO'],
            'idioma'            => (string)$dados['IDIOMA'],
            'meio_divulgacao'   => (string)$dados['MEIO-DE-DIVULGACAO'],
            'homepage'          => (string)$dados['HOME-PAGE-DO-TRABALHO'],
            'doi'               => (string)$dados['DOI'],

            // Detalhamento
            'periodico'         => (string)$detalhe['TITULO-DO-PERIODICO-OU-REVISTA'],
            'issn'              => (string)$detalhe['ISSN'],
            'volume'            => (string)$detalhe['VOLUME'],
            'serie'             => (string)$detalhe['SERIE'],
            'pagina_inicial'    => (string)$detalhe['PAGINA-INICIAL'],
            'pagina_final'      => (string)$detalhe['PAGINA-FINAL'],

            // Autor principal
            'autor_nome'        => (string)$autor['NOME-COMPLETO-DO-AUTOR'],
            'autor_citacao'     => (string)$autor['NOME-PARA-CITACAO'],
            'autor_id_cnpq'     => (string)$autor['NRO-ID-CNPQ'],
        ];
    }
    return $artigos;
}

}
