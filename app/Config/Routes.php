<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('login', 'Auth::index');
$routes->post('auth/login', 'Auth::login', ['filter' => 'csrf']);
$routes->get('logout', 'Auth::logout', ['filter' => 'auth']);
$routes->post('logout', 'Auth::logout', ['filter' => ['auth', 'csrf']]);

$routes->group('', ['filter' => 'auth'], static function ($routes): void {
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

    $routes->group('tanda-terima', static function ($routes): void {
        $routes->get('/', 'TandaTerima::index');
        $routes->get('new', 'TandaTerima::new');
        $routes->post('create', 'TandaTerima::store', ['filter' => 'csrf']);
        $routes->get('delete/(:num)', 'TandaTerima::delete/$1');
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
    
    $routes->group('uji-petik', static function ($routes): void {
        $routes->get('/', 'UjiPetik::index');
        $routes->get('new', 'UjiPetik::new');
        $routes->post('store', 'UjiPetik::store', ['filter' => 'csrf']);
        $routes->get('delete/(:num)', 'UjiPetik::delete/$1');
    });
});
