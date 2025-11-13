<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    /**
     * Verifica se o usuário logado é ADMIN
     */
    private function checkAdmin()
    {
        if (
            !$this->session->get('isLoggedIn') ||
            $this->session->get('user_perfil') !== 'admin'
        ) {
            // Redireciona com mensagem de erro
            return redirect()
                ->to('/')
                ->with('error', 'Acesso negado. Apenas administradores podem acessar esta área.');
        }
        return null;
    }

    public function index()
    {
        // 🔒 Checa permissão
        if ($redirect = $this->checkAdmin()) return $redirect;

        $model = new UserModel();
        $data['users'] = $model->findAll();

        echo view('layout/header');
        echo view('users/index', $data);
        echo view('layout/footer');
    }

    public function create()
    {
        // 🔒 Checa permissão
        if ($redirect = $this->checkAdmin()) return $redirect;

        echo view('layout/header');
        echo view('users/form');
        echo view('layout/footer');
    }

    public function store()
    {
        // 🔒 Checa permissão
        if ($redirect = $this->checkAdmin()) return $redirect;

        $model = new UserModel();

        $model->save([
            'nome' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'senha' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'perfil' => $this->request->getPost('role') ?? 'user',
        ]);

        return redirect()->to('/users');
    }
}
