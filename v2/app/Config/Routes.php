<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/(about|cyracris|team|production)', 'Home::page/$1');
$routes->get('/xsd', 'XsdViewer::index');
$routes->get('/researcher/(:any)', 'Researcher::profile/$1');
$routes->get('/extractView', 'XsdViewer::extractView');
$routes->get('/painel', 'Home::painel');
$routes->get('/', 'Home::index');
