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
        $data['producao']['cientifica'] = $LattesResearcherModel->producaoCientifica();
        $data['producao']['artistica'] = $LattesResearcherModel->producaoArtistica();

        $data['producaoAno']['cientifica'] = $LattesResearcherModel->producaoCientificaAno();
        $data['producaoAno']['artistica'] = $LattesResearcherModel->producaoArtisticaAno();

        $data['producaoIdioma']['cientifica'] = $LattesResearcherModel->producaoCientificaIdioma();
        $data['producaoIdioma']['artistica'] = $LattesResearcherModel->producaoArtisticaIdioma();

        $data['producaoCoautoria']['cientifica'] = $LattesResearcherModel->producaoCientificaCoautoria();
        $data['producaoCoautoriaAno']['cientifica'] = $LattesResearcherModel->producaoCientificaCoautoriaAno();
        $data['producaoCoautoriaPercentual']['cientifica'] = $LattesResearcherModel->producaoCientificaCoautoriaPercentual();


        echo view('layout/header', $data);

        PRE($data);

        echo view('indicators/geral_tipos', ['dados'=>$data]);
        echo view('indicators/geral_ano', ['dados' => $data]);
        echo view('indicators/geral_idioma', ['dados' => $data]);
        echo view('indicators/geral_coautoria', ['dados' => $data]);
        echo view('indicators/geral_coautoria_ano', ['dados' => $data]);
        echo view('indicators/geral_coautoria_ano_percentual', ['dados' => $data]);

        echo view('indicators/index', $data);
        echo view('layout/footer');
    }

}
