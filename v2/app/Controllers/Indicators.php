<?php

namespace App\Controllers;

use App\Models\LattesResearcherModel;

class Indicators extends BaseController
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
        $LattesResearcherModel = new LattesResearcherModel();
        $LattesResearchersAreaModel = new \App\Models\LattesResearchersAreaModel();
        $data = [];
        //$data['areas_conhecimento'] = $LattesResearcherModel->areasConhecimento();
        $data['lattes_atualizados'] = $LattesResearcherModel->atualizacaoLattes();
        $data['areas_conhecimento_all'] = $LattesResearchersAreaModel->areasConhecimentoAll();
        echo view('layout/header', $data);
        echo view('indicators/index', $data);
        echo view('layout/footer');
    }

}
