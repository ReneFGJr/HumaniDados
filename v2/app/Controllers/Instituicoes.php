<?php

namespace App\Controllers;

use App\Models\LattesResearcherModel;

class Instituicoes extends BaseController
{
    protected $model;
    protected $session;

    public function __construct()
    {
        $this->model = new LattesResearcherModel();
        $this->session = session();
    }

    // 🔹 Lista todos os pesquisadores
    public function institucicoes()
    {
        $InstituicoesModel = new \App\Models\InstituicaoLattesModel();
        $data['instituicoes'] = $InstituicoesModel->orderBy('nome_instituicao_empresa', 'ASC')->findAll();

        echo view('layout/header');
        echo view('instituicao/index', $data);
        echo view('layout/footer');
    }

    // 🔹 Lista todos os pesquisadores
    public function view($id)
    {
        $InstituicoesModel = new \App\Models\InstituicaoLattesModel();
        $data['instituicao'] = $InstituicoesModel->le($id);

        echo '<h1>'.$id.'</h1>';

        pre($data);

        echo view('layout/header');
        echo view('instituicao/view', $data);
        echo view('layout/footer');
    }

    // 🔹 Lista todos os pesquisadores
    public function index()
    {
        $data['pesquisadores'] = $this->model->orderBy('nome_completo', 'ASC')->findAll();

        echo view('layout/header');
        echo view('lattes/index', $data);
        echo view('layout/footer');
    }
}
