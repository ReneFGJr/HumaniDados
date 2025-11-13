<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    public function login()
    {
        return view('layout/header')
            . view('auth/login')
            . view('layout/footer');
    }

    public function doLogin()
    {
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');

        $user = $this->userModel->getByEmail($email);

        if ($user && password_verify($senha, $user['password'])) {
            $this->session->set([
                'isLoggedIn' => true,
                'user_id'    => $user['id'],
                'user_nome'  => $user['name'],
                'user_email' => $user['email'],
                'user_perfil' => $user['role']
            ]);
            return redirect()->to('/');
        } else {
            return redirect()->back()->with('error', 'Email ou senha inválidos.');
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }

    public function register()
    {
        return view('layout/header')
            . view('auth/register')
            . view('layout/footer');
    }

    public function doRegister()
    {
        $data = [
            'nome' => $this->request->getPost('nome'),
            'email' => $this->request->getPost('email'),
            'senha' => password_hash($this->request->getPost('senha'), PASSWORD_DEFAULT),
            'perfil' => 'user'
        ];

        $this->userModel->insert($data);
        return redirect()->to('/login')->with('success', 'Cadastro realizado com sucesso!');
    }
}
