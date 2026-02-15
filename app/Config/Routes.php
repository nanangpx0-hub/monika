<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Dashboard::index');
$routes->get('dashboard', 'Dashboard::index');

$routes->get('tanda-terima', 'TandaTerima::index');
$routes->get('tanda-terima/new', 'TandaTerima::new');
$routes->post('tanda-terima/create', 'TandaTerima::store');
$routes->get('tanda-terima/delete/(:num)', 'TandaTerima::delete/$1');

$routes->get('presensi', 'Presensi::index');
$routes->get('kartu-kendali', 'KartuKendali::index');
$routes->get('logistik', 'Logistik::index');
$routes->get('uji-petik', 'UjiPetik::index');
