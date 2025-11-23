<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <title>HumaniDados</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>

<body class="bg-dark text-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('/') ?>">HumaniDados</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('lattes') ?>">Pesquisadores</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('instituicoes') ?>">Instituições</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('producao_artistica') ?>">Produção Artística</a></li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('glossary') ?>">Glossário</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('indicators') ?>">Indicadores</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('about') ?>">Sobre</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <li class="nav-item"><a class="nav-link"><?= session()->get('user_nome') ?></a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('logout') ?>">Sair</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('login') ?>">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container my-4">