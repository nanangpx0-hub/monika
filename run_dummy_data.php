<?php
// Script untuk menjalankan seeder data dummy secara manual

// Mulai session jika belum
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Atur timezone
date_default_timezone_set('Asia/Jakarta');

// Definisikan konstanta yang dibutuhkan
define('ENVIRONMENT', 'development');
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/app/');
define('WRITEPATH', __DIR__ . '/writable/');
define('HELPERPATH', BASEPATH . 'Helpers/');

// Load composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load konfigurasi
require_once APPPATH . 'Config/Paths.php';
$paths = new Config\Paths();

// Load database
$db = \Config\Database::connect();

// Load dan jalankan seeder
$seeder = new App\Database\Seeds\DummyDataSeeder();
$seeder->run();

echo "Seeder data dummy berhasil dijalankan!";