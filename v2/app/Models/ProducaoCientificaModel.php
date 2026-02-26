<?php

namespace App\Models;

use CodeIgniter\Model;

class ProducaoCientificaModel extends Model
{
    protected $table = 'artigos_publicados';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_lattes',
        'sequencia_producao',
        'tipo',

        // Dados básicos
        'natureza',
        'atividade',
        'titulo',
        'ano',
        'pais',
        'idioma',
        'flag_relevancia',
        'titulo_ingles',
        'meio_divulgacao',
        'home_page',
        'flag_divulgacao_cientifica',

        // Detalhamento geral / Artes Visuais
        'premiacao',
        'atividade_autores',
        'instituicao_evento',
        'local_evento',
        'cidade_evento',
        'temporada',
        'informacoes_adicionais',

        // Artes Cênicas
        'tipo_evento',
        'data_estreia',
        'data_encerramento',
        'local_estreia',
        'instituicao_promotora_premio',
        'obra_referencia',
        'autor_obra_referencia',
        'ano_obra_referencia',
        'duracao',
        'flag_itinerante',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function totalProducaoCientifica()
    {
        return $this->countAllResults();
    }

    function getIndicatorByBook($type,$arg1=null,$arg2=null,$arg3=null)
    {
        $LivrosModel = new \App\Models\LivrosModel();
        $dt = [];
        $dt['livros']['trabalhos'] = $LivrosModel->select('count(*) as total, natureza')->groupBy('natureza')->findAll();
        $dt['livros']['idiomas'] = $LivrosModel->select('count(*) as total, idioma')->groupBy('idioma')->findAll();
        $dt['livros']['anos'] = $LivrosModel->select('count(*) as total, ano')->groupBy('ano')->orderBy('ano')->findAll();
        return $dt;
    }

    function getIndicatorByChapter($type,$arg1=null,$arg2=null,$arg3=null)
    {
        $CapitulosModel = new \App\Models\LivrosCapitulosModel();
        $dt = [];
        $dt['capitulos']['trabalhos'] = $CapitulosModel->select('count(*) as total, tipo as natureza')->groupBy('tipo')->findAll();
        $dt['capitulos']['idiomas'] = $CapitulosModel->select('count(*) as total, idioma')->groupBy('idioma')->findAll();
        $dt['capitulos']['anos'] = $CapitulosModel->select('count(*) as total, ano')->groupBy('ano')->orderBy('ano')->findAll();
        return $dt;
    }


    function getIndicatorByArticle($type,$arg1=null,$arg2=null,$arg3=null)
    {
        $Artigos = new \App\Models\ArtigosPublicadosModel();
        $dt = [];
        $dt['artigos']['trabalhos'] = $Artigos->select('count(*) as total, natureza')->groupBy('natureza')->findAll();
        $dt['artigos']['idiomas'] = $Artigos->select('count(*) as total, idioma')->groupBy('idioma')->findAll();
        $dt['artigos']['anos'] = $Artigos->select('count(*) as total, ano')->groupBy('ano')->orderBy('ano')->findAll();
        $dt['artigos']['periodico'] = $Artigos->select('count(*) as total, periodico')->groupBy('periodico')->orderBy('total','desc')->findAll();
        return $dt;
    }

    /** Insere autores */
    public function salvarAutores($idProducao, $autores)
    {
        $autorModel = model('ProducaoArtisticaAutoresModel');
        foreach ($autores as $a) {
            $autorModel->insert([
                'id_producao' => $idProducao,
                'nome_completo' => $a['nome_completo'],
                'nome_citacao' => $a['nome_citacao'],
                'ordem_autoria' => $a['ordem'],
                'id_cnpq' => $a['id_cnpq']
            ]);
        }
    }

    public function resume($idLattes)
    {
        $dt = $this
            ->select('tipo, natureza, count(*) as total')
            ->where('id_lattes', $idLattes)
            ->groupBy('tipo, natureza')
            ->orderBy('total desc, tipo, natureza')
            ->findAll();
        return $dt;
    }

    public function indicators()
    {
        $dt = $this
            ->select('"ARTIGO" as tipo, natureza, "Publicação" as atividade, count(*) as total')
            ->groupBy('tipo, natureza, atividade')
            ->orderBy('total desc, tipo, natureza')
            ->findAll();

        $LivrosModel = model('LivrosModel');
        $livros = $LivrosModel
            ->select('"LIVRO" as tipo, natureza, "Publicação" as atividade, count(*) as total')
            ->groupBy('tipo, natureza, atividade')
            ->orderBy('total desc, tipo, natureza')
            ->findAll();

        /*
        $CapituloLivrosModel = model('CapituloLivrosModel');
        $capitulos = $CapituloLivrosModel
            ->select('"CAPITULO" as tipo, natureza, "Publicação" as atividade, count(*) as total')
            ->groupBy('tipo, natureza, atividade')
            ->orderBy('total desc, tipo, natureza')
            ->findAll();
        */

        $dt = array_merge($dt, $livros);
        return $dt;
    }

    /** Insere keywords */
    public function salvarPalavrasChave($idProducao, $keywords)
    {
        $kwModel = model('ProducaoArtisticaKeywordsModel');
        foreach ($keywords as $k) {
            if (!empty($k)) {
                $kwModel->insert([
                    'id_producao' => $idProducao,
                    'keyword' => $k
                ]);
            }
        }
    }

    /** Insere áreas */
    public function salvarAreasConhecimento($idProducao, $areas)
    {
        $areaModel = model('ProducaoArtisticaAreasModel');
        foreach ($areas as $ar) {
            $areaModel->insert([
                'id_producao' => $idProducao,
                'grande_area' => $ar['grande_area'],
                'area' => $ar['area'],
                'sub_area' => $ar['sub_area'],
                'especialidade' => $ar['especialidade']
            ]);
        }
    }
}
