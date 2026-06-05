<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Main::index');
$routes->get('zavody', 'Main::zavody');
$routes->get('zavody/(:num)', 'Main::zavody/$1');

// HLAVNÍ ZMĚNA: Přidání routy pro zobrazení konkrétního ročníku
$routes->get('rocnik/(:num)', 'Main::rocnik/$1');

$routes->group('form-helper','' , static function ($routes){
    $routes->get('/', 'Main::index');
    
    // Routa pro zobrazení formuláře s předaným ročníkem z URL (např. form-helper/races/add/2024)
    $routes->get('races/add/(:num)', 'Main::add/$1'); 
    // Původní univerzální (pokud bys rok v URL neměl)
    $routes->get('races/add', 'Main::add');
    
    $routes->post('races/create', 'Main::create');
    
    $routes->get('races/edit/(:num)', 'Main::edit/$1');
    $routes->put('races/update/(:num)', 'Main::update/$1'); 
    $routes->get('races/delete/(:num)', 'Main::delete/$1');
});