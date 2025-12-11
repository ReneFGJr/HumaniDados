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
        $ProducaoArtisticaModel = new \App\Models\ProducaoArtisticaModel();
        $dt = $ProducaoArtisticaModel->indicators();

        echo view('layout/header');
        switch ($pg) {
            case 'musica':
                 $dt = $ProducaoArtisticaModel->getIndicatorByType($pg);
                echo view('producao_artistica/view',['array'=>$dt]);
                break;
            default:
                echo view('producao_artistica/index',['dados'=>$dt]);
                break;
        }
        echo view('layout/footer');
    }

}
