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
        $rows = $InstituicoesModel->orderBy('nome_instituicao_empresa', 'ASC')->findAll();

        $unicas = [];
        $seen = [];
        foreach ($rows as $inst) {
            $nome = trim((string) ($inst['nome_instituicao_empresa'] ?? ''));
            $codigo = trim((string) ($inst['codigo_instituicao_empresa'] ?? ''));
            $key = strtolower($codigo . '|' . preg_replace('/\s+/', ' ', $nome));

            if (isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $unicas[] = $inst;
        }

        $data['instituicoes'] = $unicas;

        echo view('layout/header');
        echo view('instituicao/index', $data);
        echo view('layout/footer');
    }

    // 🔹 Lista todos os pesquisadores
    public function view($id)
    {
        $InstituicoesModel = new \App\Models\InstituicaoLattesModel();
        $data['instituicao'] = $InstituicoesModel->le($id);

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
