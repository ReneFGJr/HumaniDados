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

    function index()
    {
        $ProducaoCientificaModel = new \App\Models\ProducaoCientificaModel();
        $dt = $ProducaoCientificaModel->indicators();

        echo view('layout/header');
        echo view('producao_cientifica/index',['dados'=>$dt]);
        echo view('layout/footer');
    }

}
