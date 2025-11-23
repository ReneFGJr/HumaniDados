<?php

namespace App\Controllers;

use App\Models\LattesResearcherModel;

class Lattes extends BaseController
{
    protected $model;
    protected $session;

    public function __construct()
    {
        $this->model = new LattesResearcherModel();
        $this->session = session();
    }

    // 🔹 Lista todos os pesquisadores
    public function index()
    {
        $data['pesquisadores'] = $this->model->orderBy('nome_completo', 'ASC')->findAll();

        echo view('layout/header');
        echo view('lattes/index', $data);
        echo view('layout/footer');
    }

    // 🔹 Exibe formulário de cadastro
    public function create()
    {
        echo view('layout/header');
        echo view('lattes/form');
        echo view('layout/footer');
    }

    // 🔹 Salva ou atualiza pesquisador
    public function store()
    {
        $id = $this->request->getPost('id');

        $data = [
            'idlattes'         => trim($this->request->getPost('idlattes')),
            'nome_completo'    => trim($this->request->getPost('nome_completo')),
            'nacionalidade'    => trim($this->request->getPost('nacionalidade')),
            'ano_doutorado'    => $this->request->getPost('ano_doutorado'),
            'ano_posdoutorado' => $this->request->getPost('ano_posdoutorado'),
            'ano_mestrado'     => $this->request->getPost('ano_mestrado'),
            'ano_graduacao'    => $this->request->getPost('ano_graduacao'),
            'data_atualizacao' => $this->request->getPost('data_atualizacao'),
            'situacao_coleta'  => $this->request->getPost('situacao_coleta') ?? 'pendente',
        ];

        if ($id) {
            $this->model->update($id, $data);
            $msg = 'Registro atualizado com sucesso!';
        } else {
            $this->model->insert($data);
            $msg = 'Pesquisador cadastrado com sucesso!';
        }

        return redirect()->to('/lattes')->with('success', $msg);
    }

    // 🔹 Edita pesquisador existente
    public function edit($id)
    {
        $data['pesquisador'] = $this->model->find($id);

        echo view('layout/header');
        echo view('lattes/form', $data);
        echo view('layout/footer');
    }

    // 🔹 Exclui pesquisador
    public function delete($id)
    {
        $this->model->delete($id);
        return redirect()->to('/lattes')->with('success', 'Registro removido com sucesso.');
    }

    // 🔹 Formulário de importação
    public function import()
    {
        echo view('layout/header');
        echo view('lattes/import');
        echo view('layout/footer');
    }

    function harvesting()
    {
        $Lattes = new LattesResearcherModel();
        echo view('layout/header');
        echo view('lattes/painel_harvesting');
        echo view('layout/footer');

        $msg = $Lattes->harvestDados();
        exit;
    }

    public function verifyFiles()
    {
        $Lattes = new LattesResearcherModel();
        $msg = $Lattes->verificarArquivos();
        pre($msg);
        return redirect()->to('/lattes')->with('success', $msg);
    }

    public function process($idlattes)
    {
        $Lattes = new LattesResearcherModel();
        $msg = $Lattes->processarXML($idlattes);

        $dt = $Lattes->where('idlattes', $idlattes)->first();
        return redirect()->to('/lattes/view/'.$dt['id'])->with('success', $msg);
    }

    public function extractor($idlattes)
    {
        $Lattes = new LattesResearcherModel();
        $msg = $Lattes->extrairDados($idlattes);

        $dt = $Lattes->where('idlattes', $idlattes)->first();
        return redirect()->to('/lattes/view/'.$dt['id'])->with('success', $msg);
    }

    public function show($id)
    {
        $Lattes = new LattesResearcherModel();
        $InstituicoesModel = new \App\Models\InstituicaoLattesModel();

        $data['pesquisador'] = $Lattes->le($id);
        $data['treeHTML'] = $Lattes->xml_content($id);

        $src = view('layout/header');
        $src .= view('lattes/view', $data);
        $src .= view('layout/footer');
        return $src;
    }

    // 🔹 Processa a importação dos IDs
    public function doImport()
    {
        $Lattes = new LattesResearcherModel();
        $texto = trim($this->request->getPost('idlattes_lista'));

        if (empty($texto)) {
            return redirect()->back()->with('error', 'Nenhum ID informado.');
        }

        $linhas = preg_split('/\r\n|\r|\n/', $texto);
        $importados = 0;
        $existentes = 0;

        foreach ($linhas as $linha) {
            $id = trim($linha);
            if ($id === '') continue;

            // Verifica digito verificador e ajusta formato
            if ($Lattes->validarIDLattes($id)) {
                echo "OK";
            } else {
                echo "ERRO";
                print_r("ID Lattes inválido: {$id}");
                exit;
                continue; // Pula IDs inválidos
            }

            // Verifica se já existe
            $existe = $this->model->where('idlattes', $id)->first();
            if (!$existe) {
                $this->model->insert([
                    'idlattes' => $id,
                    'situacao_coleta' => 'pendente'
                ]);
                $importados++;
            } else {
                $existentes++;
            }
        }

        return redirect()->to('/lattes')->with(
            'success',
            "Importação concluída. <br>✅ Novos: {$importados} <br>⚠️ Ignorados: {$existentes}"
        );
    }
}
