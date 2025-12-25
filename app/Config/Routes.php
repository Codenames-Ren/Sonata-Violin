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
    $routes->get('/dashboard', 'Dashboard::index', ['filter' => 'role:admin,operator']);
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
    $routes->post('status/(:num)', 'PaketController::status/$1');
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

    $routes->post('mundur/(:num)', 'PendaftaranController::mengundurkanDiri/$1');

});

$routes->post('daftar', 'PendaftaranSiswaController::create');

$routes->get('pembayaran', 'PembayaranController::index');
$routes->post('pembayaran/verify/(:num)', 'PembayaranController::verify/$1');
$routes->post('pembayaran/reject/(:num)', 'PembayaranController::reject/$1');
$routes->post('pembayaran/resubmit/(:num)', 'PembayaranController::resubmit/$1');

$routes->group('jadwal-kelas', ['filter' => 'auth'], function ($routes) {
    $routes->group('', ['filter' => 'role:admin,operator,instruktur'], function ($routes) {
        $routes->get('/', 'JadwalKelasController::index');
        $routes->get('(:num)', 'JadwalKelasController::detail/$1');
    });
    
    // Routes khusus admin dan operator untuk jadwal kelas
    $routes->group('', ['filter' => 'role:admin,operator'], function ($routes) {
        $routes->post('create', 'JadwalKelasController::create');
        $routes->post('update/(:num)', 'JadwalKelasController::update/$1');
        $routes->post('(:num)', 'JadwalKelasController::delete/$1');
        $routes->delete('(:num)', 'JadwalKelasController::delete/$1');
        $routes->post('assign-siswa', 'JadwalKelasController::assignSiswa');
        $routes->post('remove-siswa/(:num)/(:num)', 'JadwalKelasController::removeSiswa/$1/$2');
    });
});

$routes->group('absensi', ['filter' => 'auth'], function ($routes) {
    // Routes untuk semua role (admin, operator, instruktur)
    $routes->group('', ['filter' => 'role:admin,operator,instruktur'], function ($routes) {
        $routes->get('/', 'AbsensiController::index');
        $routes->get('detail/(:num)', 'AbsensiController::detail/$1');
    });
    
    // Routes khusus admin dan operator untuk manage absensi
    $routes->group('', ['filter' => 'role:admin,operator'], function ($routes) {
        $routes->get('create', 'AbsensiController::create');
        $routes->post('open/(:num)', 'AbsensiController::open/$1');
        $routes->post('close/(:num)', 'AbsensiController::close/$1');
    });
    
    // Routes khusus instruktur untuk isi absensi siswa
    $routes->group('', ['filter' => 'role:instruktur'], function ($routes) {
        $routes->post('submit', 'AbsensiController::submit');
    });
});

$routes->group('progress-kursus', ['filter' => 'auth'], function ($routes) {
    // Routes untuk semua role (admin, operator, instruktur)
    $routes->group('', ['filter' => 'role:admin,operator,instruktur'], function ($routes) {
        $routes->get('/', 'ProgressKursusController::index');
        $routes->get('detail/(:num)', 'ProgressKursusController::detail/$1');
    });
    
    // Routes khusus admin dan operator (create progress baru)
    $routes->group('', ['filter' => 'role:admin,operator'], function ($routes) {
        $routes->post('create', 'ProgressKursusController::create');
    });
    
    // Routes khusus instruktur (CRUD detail pertemuan)
    $routes->group('', ['filter' => 'role:instruktur'], function ($routes) {
        $routes->post('detail/(:num)/create', 'ProgressKursusController::createDetail/$1');
        $routes->post('detail/(:num)/update/(:num)', 'ProgressKursusController::updateDetail/$1/$2');
    });
});

$routes->group('laporan', ['filter' => 'auth'], function($routes) {
    $routes->group('', ['filter' => 'role:admin,operator'], function ($routes) {
        $routes->get('/', 'LaporanController::index');
        $routes->get('profit', 'LaporanController::profit');
        $routes->get('pendaftaran', 'LaporanController::pendaftaran');
        $routes->get('absensi', 'LaporanController::absensi');
        $routes->get('progress', 'LaporanController::progress');
    });
});

$routes->group('sertifikat', ['filter' => 'auth'], function($routes) {
    $routes->group('', ['filter' => 'role:admin,operator'], function ($routes) { 
        $routes->get('/', 'SertifikatController::index');    
        $routes->get('siswa-lulus', 'SertifikatController::siswaLulus');
        
        // Generate sertifikat
        $routes->post('generate/(:num)', 'SertifikatController::generateSingle/$1');
        $routes->post('generate-batch', 'SertifikatController::generateBatch');
        
        // Cetak sertifikat (download PDF)
        $routes->get('cetak/(:num)', 'SertifikatController::cetak/$1');
        $routes->post('cetak-batch', 'SertifikatController::cetakBatch');
        $routes->get('preview/(:num)', 'SertifikatController::preview/$1');

        // Delete sertifikat
        $routes->post('delete/(:num)', 'SertifikatController::delete/$1');
        $routes->delete('delete/(:num)', 'SertifikatController::delete/$1');
        $routes->get('statistik', 'SertifikatController::getStatistik');
    });
});