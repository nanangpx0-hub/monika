<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('login', 'Auth::index');
$routes->post('auth/login', 'Auth::login', ['filter' => 'csrf']);
$routes->get('register', 'Auth::registerForm');
$routes->post('register', 'Auth::register', ['filter' => 'csrf']);
$routes->get('logout', 'Auth::logout', ['filter' => 'auth']);
$routes->post('logout', 'Auth::logout', ['filter' => ['auth', 'csrf']]);

$routes->group('', ['filter' => 'auth'], static function ($routes): void {
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

    $routes->group('tanda-terima', static function ($routes): void {
        $routes->get('/', 'TandaTerima::index');
        $routes->get('new', 'TandaTerima::new');
        $routes->get('edit/(:num)', 'TandaTerima::edit/$1');
        $routes->post('create', 'TandaTerima::store', ['filter' => 'csrf']);
        $routes->post('update/(:num)', 'TandaTerima::update/$1', ['filter' => 'csrf']);
        $routes->post('delete/(:num)', 'TandaTerima::delete/$1', ['filter' => 'csrf']);
    });

    $routes->get('presensi', 'Presensi::index');
    $routes->post('presensi/submit', 'Presensi::submit', ['filter' => 'csrf']);
    
    $routes->group('kartu-kendali', static function ($routes): void {
        $routes->get('/', 'KartuKendali::index');
        $routes->get('detail/(:segment)', 'KartuKendali::detail/$1');
        $routes->post('store', 'KartuKendali::store', ['filter' => 'csrf']);
        $routes->post('delete', 'KartuKendali::delete', ['filter' => 'csrf']);
    });
    
    $routes->get('logistik', 'Logistik::index');

    $routes->group('dokumen', static function ($routes): void {
        $routes->get('/', 'Dokumen::index');
        $routes->get('create', 'Dokumen::create');
        $routes->post('store', 'Dokumen::store', ['filter' => 'csrf']);
        $routes->post('mark-entry/(:num)', 'Dokumen::markEntry/$1', ['filter' => 'csrf']);
        $routes->post('report-error', 'Dokumen::reportError', ['filter' => 'csrf']);
        $routes->get('download-template', 'Dokumen::downloadTemplate');
        $routes->post('import-preview', 'Dokumen::importPreview', ['filter' => 'csrf']);
        $routes->post('import-store', 'Dokumen::importStore', ['filter' => 'csrf']);
    });

    $routes->group('penyetoran', static function ($routes): void {
        $routes->get('/', 'PenyetoranDokumen::index');
        $routes->get('create', 'PenyetoranDokumen::create');
        $routes->post('store', 'PenyetoranDokumen::store', ['filter' => 'csrf']);
        $routes->get('detail/(:num)', 'PenyetoranDokumen::detail/$1');
        $routes->post('confirm/(:num)', 'PenyetoranDokumen::confirm/$1', ['filter' => 'csrf']);
        $routes->get('download-template', 'PenyetoranDokumen::downloadTemplate');
        $routes->post('import-preview', 'PenyetoranDokumen::importPreview', ['filter' => 'csrf']);
    });

    $routes->group('kegiatan', static function ($routes): void {
        $routes->get('/', 'Kegiatan::index');
        $routes->post('store', 'Kegiatan::store', ['filter' => 'csrf']);
        $routes->post('status/(:num)', 'Kegiatan::updateStatus/$1', ['filter' => 'csrf']);
        $routes->post('delete/(:num)', 'Kegiatan::delete/$1', ['filter' => 'csrf']);
    });

    $routes->group('laporan', static function ($routes): void {
        $routes->get('/', 'Laporan::index');
        $routes->get('pcl', 'Laporan::pcl');
        $routes->get('pengolahan', 'Laporan::pengolahan');
        $routes->get('export-excel', 'Laporan::exportExcel');
        $routes->get('export-pdf', 'Laporan::exportPdf');
    });

    $routes->get('monitoring', 'Monitoring::index');

    $routes->group('users', static function ($routes): void {
        $routes->get('/', 'UserManagement::index');
        $routes->post('store', 'UserManagement::store', ['filter' => 'csrf']);
        $routes->post('update/(:num)', 'UserManagement::update/$1', ['filter' => 'csrf']);
        $routes->post('reset-password/(:num)', 'UserManagement::resetPassword/$1', ['filter' => 'csrf']);
        $routes->post('toggle-active/(:num)', 'UserManagement::toggleActive/$1', ['filter' => 'csrf']);
        $routes->post('delete/(:num)', 'UserManagement::delete/$1', ['filter' => 'csrf']);
    });
    
    $routes->group('uji-petik', static function ($routes): void {
        $routes->get('/', 'UjiPetik::index');
        $routes->get('new', 'UjiPetik::new');
        $routes->get('edit/(:num)', 'UjiPetik::edit/$1');
        $routes->post('store', 'UjiPetik::store', ['filter' => 'csrf']);
        $routes->post('update/(:num)', 'UjiPetik::update/$1', ['filter' => 'csrf']);
        $routes->post('delete/(:num)', 'UjiPetik::delete/$1', ['filter' => 'csrf']);
    });
});
