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
        $busca = trim((string) $this->request->getGet('q'));

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

        if ($busca !== '') {
            $buscaNormalizada = mb_strtolower($busca, 'UTF-8');

            $unicas = array_values(array_filter($unicas, static function (array $inst) use ($buscaNormalizada): bool {
                $campos = [
                    $inst['nome_instituicao_empresa'] ?? '',
                    $inst['pais'] ?? '',
                    $inst['uf'] ?? '',
                    $inst['cidade'] ?? '',
                ];

                foreach ($campos as $campo) {
                    $valor = mb_strtolower(trim((string) $campo), 'UTF-8');
                    if ($valor !== '' && mb_strpos($valor, $buscaNormalizada, 0, 'UTF-8') !== false) {
                        return true;
                    }
                }

                return false;
            }));
        }

        $data['instituicoes'] = $unicas;
        $data['busca'] = $busca;

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
