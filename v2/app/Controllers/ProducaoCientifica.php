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

        $dt = $IndicadoresModel->findByArgs($arg0, $arg1, $arg2, $arg3);
        if ($dt == null)
        {
            $dt = $ProducaoCientificaModel->getIndicatorByArticle($pg, $arg1, $arg2, $arg3);
            $IndicadoresModel->saveIndicador($arg0, $arg1, $arg2, $arg3, $dt);
        }

        echo view('layout/header');
        switch ($pg) {
            case 'artigos':
                echo view('producao_cientifica/view',['array'=>$dt, 'pag'=>'artigos']);
                break;
                          
            default:
                ECHO $pg;
                break;
        }
        echo view('layout/footer');
    }

}
