<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'AuthController::index');
$routes->post('/login', 'AuthController::loginProcess');
$routes->post('/register', 'AuthController::registerProcess');
$routes->get('/logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/dashboard', 'Dashboard::index');
});

$routes->group('paket', ['filter' => 'admin'], function($routes){
    $routes->get('/', 'PaketController::paket');
});

$routes->group('settings', ['filter' => 'admin'], function($routes) {
    $routes->get('operators', 'OperatorController::operators');
    $routes->post('operators/create', 'OperatorController::create');
    $routes->post('operators/update/(:num)', 'OperatorController::update/$1');
    $routes->post('operators/delete/(:num)', 'OperatorController::delete/$1');
    $routes->post('operators/toggle-status/(:num)', 'OperatorController::toggleStatus/$1');
});

$routes->group('paket', ['filter' => 'admin'], function($routes) {
    $routes->get('/', 'PaketController::paket');
    $routes->post('create', 'PaketController::create');
    $routes->post('update/(:num)', 'PaketController::update/$1');
    $routes->post('delete/(:num)', 'PaketController::delete/$1');
});

$routes->group('siswa', ['filter' => 'admin'], function($routes) {
    $routes->get('/', 'SiswaController::siswa');
    $routes->post('create', 'SiswaController::create');
    $routes->post('update/(:num)', 'SiswaController::update/$1');
    $routes->post('delete/(:num)', 'SiswaController::delete/$1');
    $routes->post('toggle-status/(:num)', 'SiswaController::toggleStatus/$1');
});

$routes->group('instruktur', ['filter' => 'admin'], function($routes) {
    $routes->get('/', 'InstrukturController::instruktur');
    $routes->post('create', 'InstrukturController::create');
    $routes->post('update/(:num)', 'InstrukturController::update/$1');
    $routes->post('delete/(:num)', 'InstrukturController::delete/$1');
    $routes->post('toggle-status/(:num)', 'InstrukturController::toggleStatus/$1');
});