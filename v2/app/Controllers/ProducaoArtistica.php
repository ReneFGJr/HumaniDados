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

    function index()
    {
        $ProducaoArtisticaModel = new \App\Models\ProducaoArtisticaModel();
        $dt = $ProducaoArtisticaModel->indicators();

        echo view('layout/header');
        echo view('producao_artistica/index',['dados'=>$dt]);
        echo view('layout/footer');
    }

}
