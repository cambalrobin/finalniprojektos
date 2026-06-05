<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Main::index');
$routes->get('rocnik/(:num)', 'Main::rocnik/$1');

$routes->get('zavody/(:num)', 'Main::zavody/$1');

$routes->group('form-helper', '', static function ($routes) {
    
    $routes->get('races/add/(:num)', 'Main::add/$1');
    $routes->get('races/add', 'Main::add');

    $routes->post('races/create', 'Main::create');

    $routes->get('races/edit/(:num)', 'Main::edit/$1');
    $routes->put('races/update/(:num)', 'Main::update/$1');
    $routes->get('races/delete/(:num)', 'Main::delete/$1');
});