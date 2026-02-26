<?php

namespace App\Controllers;

use App\Models\LattesResearcherModel;

class ProducaoCientifica extends BaseController
{
    protected $model;
    protected $session;

    public function __construct()
    {
        $this->model = new LattesResearcherModel();
        $this->session = session();
    }


    function index($pg='')
    {
        $IndicadoresModel = new \App\Models\IndicadoresModel();
        $ProducaoCientificaModel = new \App\Models\ProducaoCientificaModel();
        $arg1 = $this->request->getGet('arg1');
        $arg2 = $this->request->getGet('arg2');
        $arg3 = $this->request->getGet('arg3');
        $arg0 = $pg;

        $rsp = '';

        $dt = $IndicadoresModel->findByArgs($arg0, $arg1, $arg2, $arg3);
        if ($dt == null)
        {
            switch($pg) {
                case 'artigos':
                    $dt = $ProducaoCientificaModel->getIndicatorByArticle($pg, $arg1, $arg2, $arg3);
                    break;
                case 'livros':
                    $dt = $ProducaoCientificaModel->getIndicatorByBook($pg, $arg1, $arg2, $arg3);
                    break;
                case 'capitulos':
                    $dt = $ProducaoCientificaModel->getIndicatorByChapter($pg, $arg1, $arg2, $arg3);
                    break;
            }
            $IndicadoresModel->saveIndicador($arg0, $arg1, $arg2, $arg3, $dt);
        }



        /* Render view */
        switch($pg) {
            case 'artigos':
                $rsp .= view('producao_cientifica/indicador_artigos', ['artigos' => $dt['artigos']['trabalhos'], 'pag' => $pg]);
                $rsp .= view('producao_cientifica/indicador_idioma', ['artigos' => $dt['artigos']['idiomas'], 'pag' => $pg]);
                $rsp .= view('producao_cientifica/indicador_ano', ['anos' => $dt['artigos']['anos'], 'pag' => $pg]);
                break;
            case 'livros':
                $rsp .= view('producao_cientifica/indicador_livros', ['livros' => $dt['livros']['trabalhos'], 'pag' => $pg]);
                $rsp .= view('producao_cientifica/indicador_idioma', ['artigos' => $dt['livros']['idiomas'], 'pag' => $pg]);
                $rsp .= view('producao_cientifica/indicador_ano', ['anos' => $dt['livros']['anos'], 'pag' => $pg]);
                break;
            case 'capitulos':
                $rsp .= view('producao_cientifica/indicador_capitulos', ['capitulos' => $dt['capitulos']['trabalhos'], 'pag' => $pg]);
                $rsp .= view('producao_cientifica/indicador_idioma', ['artigos' => $dt['capitulos']['idiomas'], 'pag' => $pg]);
                $rsp .= view('producao_cientifica/indicador_ano', ['anos' => $dt['capitulos']['anos'], 'pag' => $pg]);
                break;
        }

        echo view('layout/header');
        echo $rsp;
        echo view('layout/footer');
    }

}
