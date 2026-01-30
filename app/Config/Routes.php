<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Auth Routes
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::loginProcess');
$routes->get('/register', 'Auth::register', ['filter' => 'auth']);
$routes->post('/register', 'Auth::registerProcess', ['filter' => 'auth']);
$routes->get('/logout', 'Auth::logout');

// Redirect root to login if not authenticated
$routes->get('/', function () {
    if (session()->get('is_logged_in')) {
        return redirect()->to('dashboard');
    }
    return redirect()->to('login');
});

// Dashboard route
$routes->get('/dashboard', 'Home::index', ['filter' => 'auth']);

// Kegiatan Routes
$routes->group('kegiatan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Kegiatan::index');
    $routes->post('store', 'Kegiatan::store');
    $routes->post('status/(:num)', 'Kegiatan::updateStatus/$1');
    $routes->get('delete/(:num)', 'Kegiatan::delete/$1');
});

// Dokumen Routes
$routes->group('dokumen', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Dokumen::index');
    $routes->get('create', 'Dokumen::create');
    $routes->post('store', 'Dokumen::store');
    $routes->post('mark-entry/(:num)', 'Dokumen::markEntry/$1');
    $routes->post('mark-clean/(:num)', 'Dokumen::markClean/$1');
    $routes->get('edit/(:num)', 'Dokumen::edit/$1');
    $routes->post('update/(:num)', 'Dokumen::update/$1');
    $routes->post('delete/(:num)', 'Dokumen::delete/$1');
    $routes->post('reset-status/(:num)', 'Dokumen::resetStatus/$1');
    $routes->post('report-error', 'Dokumen::reportError');
});

// Laporan Routes
$routes->group('laporan', ['filter' => 'auth'], function ($routes) {
    $routes->get('pcl', 'Laporan::pcl');
    $routes->get('pengolahan', 'Laporan::pengolahan');
});

// Monitoring Routes
$routes->get('/monitoring', 'Monitoring::index', ['filter' => 'auth']);

// API Routes
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->post('auth/login', 'Auth::login');
    $routes->group('users', ['filter' => 'jwt'], function ($routes) {
        $routes->get('/', 'Users::index');
        $routes->get('(:num)', 'Users::show/$1');
        $routes->post('/', 'Users::create');
        $routes->put('(:num)', 'Users::update/$1');
        $routes->delete('(:num)', 'Users::delete/$1');
    });
});

// Admin Routes
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => ['auth', 'admin']], function ($routes) {
    // User Management
    $routes->get('users', 'Users::index');
    $routes->get('users/create', 'Users::create');
    $routes->post('users/store', 'Users::store');
    $routes->get('users/edit/(:num)', 'Users::edit/$1');
    $routes->post('users/update/(:num)', 'Users::update/$1');
    $routes->delete('users/delete/(:num)', 'Users::delete/$1');
    $routes->get('users/reset-password/(:num)', 'Users::resetPasswordForm/$1');
    $routes->post('users/reset-password/(:num)', 'Users::resetPassword/$1');
    $routes->get('users/toggle-status/(:num)', 'Users::toggleStatus/$1');

    // Audit Trail
    $routes->get('audit', 'Audit::index');
    $routes->get('audit/export', 'Audit::export');
    $routes->get('audit/detail/(:num)', 'Audit::detail/$1');

    // PCL Management
    $routes->get('pcl', 'Pcl::index');
    $routes->get('pcl/create', 'Pcl::create');
    $routes->post('pcl/store', 'Pcl::store');
    $routes->get('pcl/edit/(:num)', 'Pcl::edit/$1');
    $routes->post('pcl/update/(:num)', 'Pcl::update/$1');
    $routes->get('pcl/delete/(:num)', 'Pcl::delete/$1');
    $routes->get('pcl/export', 'Pcl::export');
    $routes->get('pcl/import', 'Pcl::importForm');
    $routes->post('pcl/import', 'Pcl::import');
    $routes->get('pcl/template', 'Pcl::downloadTemplate');

    // PML Management
    $routes->get('pml', 'Pml::index');
    $routes->get('pml/create', 'Pml::create');
    $routes->post('pml/store', 'Pml::store');
    $routes->get('pml/edit/(:num)', 'Pml::edit/$1');
    $routes->post('pml/update/(:num)', 'Pml::update/$1');
    $routes->get('pml/delete/(:num)', 'Pml::delete/$1');
    $routes->get('pml/monitoring/(:num)', 'Pml::monitoring/$1');
    $routes->get('pml/performance/(:num)', 'Pml::performanceReport/$1');
    $routes->get('pml/export-performance/(:num)', 'Pml::exportPerformance/$1');
    $routes->post('pml/assign-pcl', 'Pml::assignPcl');
    $routes->get('pml/unassign-pcl/(:num)', 'Pml::unassignPcl/$1');
    $routes->post('pml/log-activity', 'Pml::logActivity');
});

// API Documentation (public access)
$routes->get('api/docs', 'Api\Docs::index');


