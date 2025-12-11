<?php

namespace App\Controllers;

use App\Models\LattesResearcherModel;

class ProducaoArtistica extends BaseController
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
        $ProducaoArtisticaModel = new \App\Models\ProducaoArtisticaModel();
        $arg1 = $this->request->getGet('arg1');
        $arg2 = $this->request->getGet('arg2');
        $arg3 = $this->request->getGet('arg3');
        $arg0 = $pg;

        $dt = $IndicadoresModel->findByArgs($arg0, $arg1, $arg2, $arg3);
        if ($dt == null)
        {
            $dt = $ProducaoArtisticaModel->getIndicatorByType($pg, $arg1, $arg2, $arg3);
            $IndicadoresModel->saveIndicador($arg0, $arg1, $arg2, $arg3, $dt);
        }
        
        echo view('layout/header');
        switch ($pg) {
            case 'musica':
                echo view('producao_artistica/view',['array'=>$dt, 'pag'=>'musica']);
                break;
            case 'ARTES-CENICAS':
                echo view('producao_artistica/view',['array'=>$dt, 'pag'=>'ARTES-CENICAS']);
                break;
            case 'ARTES-VISUAIS':
                echo view('producao_artistica/view',['array'=>$dt, 'pag'=>'ARTES-CENICAS']);
                break;      
            case 'OUTROS':
                echo view('producao_artistica/view',['array'=>$dt, 'pag'=>'ARTES-CENICAS']);
                break;                           
            default:
                ECHO $pg;
                break;
        }
        echo view('layout/footer');
    }

}
