<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('glossary', 'Home::glossary');
$routes->get('faq', 'Home::faq');

// Grupo de rotas de usuários
$routes->group(
    'users',
    ['namespace' => 'App\Controllers'],
    function ($routes) {
        $routes->get('/', 'Users::index');          // Listar usuários
        $routes->get('create', 'Users::create');    // Formulário de novo usuário
        $routes->post('store', 'Users::store');     // Salvar usuário
    }
);

$routes->group(
    'instituicoes',
    ['namespace' => 'App\Controllers'],
    function ($routes) {
        $routes->get('/', 'Instituicoes::institucicoes');
        $routes->get('view/(:num)', 'Instituicoes::view/$1');
    }
);

$routes->group(
    'producao_artistica',
    ['namespace' => 'App\Controllers'],
    function ($routes) {
        $routes->get('/', 'ProducaoArtistica::index');
        $routes->get('view/(:num)', 'Indicators::view/$1');
    }
);

$routes->group(
    'producao_cientifica',
    ['namespace' => 'App\Controllers'],
    function ($routes) {
        $routes->get('/', 'ProducaoCientifica::index');
        $routes->get('view/(:num)', 'Indicators::view/$1');
    }
);


$routes->group(
    'indicators',
    ['namespace' => 'App\Controllers'],
    function ($routes) {
        $routes->get('/', 'Indicators::index');
        $routes->get('view/(:num)', 'Indicators::view/$1');
    }
);


$routes->group('lattes', function ($routes) {
    $routes->get('/', 'Lattes::index');
    $routes->get('create', 'Lattes::create');
    $routes->post('store', 'Lattes::store');
    $routes->get('edit/(:num)', 'Lattes::edit/$1');
    $routes->get('delete/(:num)', 'Lattes::delete/$1');
    $routes->get('import', 'Lattes::import');
    $routes->post('import', 'Lattes::doImport');
    $routes->get('verify-files', 'Lattes::verifyFiles');
    $routes->get('view/(:num)', 'Lattes::show/$1');
    $routes->get('extractor/(:num)', 'Lattes::extractor/$1');
    $routes->get('process/(:num)', 'Lattes::process/$1');
    $routes->get('harvesting', 'Lattes::harvesting');
});

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::doLogin');
$routes->get('logout', 'Auth::logout');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::doRegister');
