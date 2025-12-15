<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'HomeController::index');
$routes->get('/login', 'AuthController::index');
$routes->post('auth/loginProcess', 'AuthController::loginProcess');
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
    $routes->post('operators/check-username', 'OperatorController::checkUsername');
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

$routes->group('ruang-kelas', ['filter' => 'admin'], function($routes) {
    $routes->get('/', 'RuangKelasController::ruangKelas');
    $routes->post('create', 'RuangKelasController::create');
    $routes->post('update/(:num)', 'RuangKelasController::update/$1');
    $routes->post('delete/(:num)', 'RuangKelasController::delete/$1');
    $routes->post('toggle-status/(:num)', 'RuangKelasController::toggleStatus/$1');
});

$routes->group('pendaftaran', ['filter' => 'transaksi'], function($routes) {
    $routes->get('/', 'PendaftaranController::index');

    $routes->post('create', 'PendaftaranController::create');
    $routes->post('update/(:num)', 'PendaftaranController::update/$1');

    $routes->post('verifikasi/(:num)', 'PendaftaranController::verifikasi/$1');
    $routes->post('batal/(:num)', 'PendaftaranController::batalkan/$1');
    $routes->post('selesai/(:num)', 'PendaftaranController::selesai/$1');

    $routes->post('delete/(:num)', 'PendaftaranController::delete/$1');
    $routes->post('mundur/(:num)', 'PendaftaranController::mengundurkanDiri/$1');

});

$routes->post('daftar', 'PendaftaranSiswaController::create');

$routes->get('pembayaran', 'PembayaranController::index');
$routes->post('pembayaran/verify/(:num)', 'PembayaranController::verify/$1');
$routes->post('pembayaran/reject/(:num)', 'PembayaranController::reject/$1');
$routes->post('pembayaran/resubmit/(:num)', 'PembayaranController::resubmit/$1');

