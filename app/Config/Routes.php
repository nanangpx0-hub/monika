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
    $routes->get('users', 'Users::index');
    $routes->get('users/create', 'Users::create');
    $routes->post('users/store', 'Users::store');
    $routes->get('users/edit/(:num)', 'Users::edit/$1');
    $routes->post('users/update/(:num)', 'Users::update/$1');
    $routes->delete('users/delete/(:num)', 'Users::delete/$1');

    // Audit Report
    $routes->get('audit', 'Audit::index');
});

