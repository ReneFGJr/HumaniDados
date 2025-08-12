<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/(about|cyracris|team|production)', 'Home::page/$1');
$routes->get('/', 'Home::index');
