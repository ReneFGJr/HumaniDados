<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <title>HumaniDados</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/favicon.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('assets/favicon.png') ?>">

</head>

<body class="bg-light text-dark">

<nav class="navbar navbar-expand-lg navbar-dark bg-navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('/') ?>">HumaniDados</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHD">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarHD">

            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('lattes') ?>">Pesquisadores</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('instituicoes') ?>">Instituições</a>
                </li>

                <!-- PRODUÇÃO ARTÍSTICA DROPDOWN CORRIGIDA -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropArt" data-bs-toggle="dropdown">
                        Produção Artística
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="dropArt">

                        <li><a class="dropdown-item" href="<?= base_url('producao_artistica/MUSICA') ?>">Música</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('producao_artistica/ARTES-CENICAS') ?>">Artes Cênicas</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('producao_artistica/ARTES-VISUAIS') ?>">Artes Visuais</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('producao_artistica/OUTROS') ?>">Outros</a></li>

                    </ul>
                </li>

                 <!-- PRODUÇÃO CIENTIFICA DROPDOWN CORRIGIDA -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropCientifica" data-bs-toggle="dropdown">
                        Produção Bibliográfica
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="dropCientifica">

                        <li><a class="dropdown-item" href="<?= base_url('producao_cientifica/artigos') ?>">Artigos</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('producao_cientifica/livros') ?>">Livros</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('producao_cientifica/capitulos') ?>">Capítulos de Livros</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('producao_cientifica/eventos') ?>">Eventos</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('producao_cientifica/partituras') ?>">Partituras</a></li>

                    </ul>
                </li>

                 <!-- PRODUÇÃO CIENTIFICA DROPDOWN CORRIGIDA -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropCientifica" data-bs-toggle="dropdown">
                        Produção Técnica
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="dropCientifica">
                        <li><a class="dropdown-item" href="<?= base_url('producao_tecnica/orientacoes') ?>">Orientações</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('producao_cientifica/artigos') ?>">Entrevistas & Mesas redondas</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('producao_cientifica/livros') ?>">Editoração</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('producao_cientifica/livros') ?>">Manutenção de Obra artística</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('producao_cientifica/livros') ?>">Outros</a></li>
                    </ul>
                </li>                
            </ul>

            <!-- Segundo grupo -->
            <ul class="navbar-nav ms-3">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('indicators') ?>">Indicadores</a></li>
                 <!-- PRODUÇÃO TÉCNICA DROPDOWN CORRIGIDA -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropAbout" data-bs-toggle="dropdown">
                        Sobre
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="dropAbout">
                        <li><a class="dropdown-item" href="<?= base_url('glossary') ?>">Glossário</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('about') ?>">Sobre o projeto</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('faq') ?>">FAQ</a></li>
                    </ul>
                </li>                   
            </ul>

            <!-- Login -->
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
