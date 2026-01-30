<?php
// bootstrap_seeder.php - File untuk menjalankan seeder

// Atur path
$basePath = __DIR__;
$appPath = $basePath . '/app';
$systemPath = $basePath . '/vendor/codeigniter4/framework/system';

// Pastikan kita berada di environment development
define('ENVIRONMENT', 'development');

// Konfigurasi path
require_once $systemPath . '/CodeIgniter.php';
require_once $appPath . '/Config/Paths.php';

$paths = new Config\Paths();
$paths->systemDirectory = $systemPath;
$paths->applicationDirectory = $appPath;
$paths->writableDirectory = $basePath . '/writable';
$paths->testsDirectory = $basePath . '/tests';

// Bootstrap CodeIgniter
require_once $systemPath . '/bootstrap.php';

// Load servis database
$db = \Config\Database::connect();

// Load dan jalankan seeder
$seeder = \CodeIgniter\Database\Seeder::load('App\Database\Seeds\DummyDataSeeder', $db);
$seeder->run();

echo "Seeder data dummy berhasil dijalankan!";