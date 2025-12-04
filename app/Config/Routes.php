<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/login', 'AuthController::index');
$routes->post('/login', 'AuthController::loginProcess');
$routes->post('/register', 'AuthController::registerProcess');
$routes->get('/logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/dashboard', 'DashboardController::index');
});

$routes->group('paket', ['filter' => 'admin'], function($routes){
    $routes->get('/', 'PaketController::index');
});


$routes->group('settings', ['filter' => 'admin'], function($routes) {
    $routes->get('operators', 'OperatorController::operators');
    $routes->post('operators/create', 'OperatorController::create');
    $routes->post('operators/update/(:num)', 'OperatorController::update/$1');
    $routes->post('operators/delete/(:num)', 'OperatorController::delete/$1');
    $routes->post('operators/toggle-status/(:num)', 'OperatorController::toggleStatus/$1');
});
