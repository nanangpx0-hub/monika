<?php
// File: c:\laragon\www\monika\large_seed_runner.php

// Mulai output buffering
ob_start();

// Atur timezone
date_default_timezone_set('Asia/Jakarta');

// Definisikan environment
define('ENVIRONMENT', 'development');

// Load autoload Composer
require_once __DIR__ . '/vendor/autoload.php';

// Load konfigurasi CodeIgniter
require_once __DIR__ . '/app/Config/Paths.php';
$paths = new Config\Paths();

// Bootstrap CodeIgniter
require_once $paths->systemDirectory . '/bootstrap.php';

// Load service database
$db = \Config\Database::connect();

// Load dan jalankan seeder skala besar
try {
    echo "<h2>Menjalankan Seeder Data Dummy Skala Besar...</h2>\n";

    // Jalankan seeder skala besar
    $seeder = \CodeIgniter\Database\Seeder::load('App\Database\Seeds\LargeScaleTestDataSeeder');
    $seeder->run();

    echo "<p><strong>Seeder skala besar berhasil dijalankan!</strong></p>\n";
    echo "<p>Data dummy dalam jumlah besar telah dimasukkan ke dalam database.</p>\n";
    echo "<p>Jumlah dokumen: 500 record</p>\n";
    echo "<p>Jumlah anomali: 200 record</p>\n";

} catch (Exception $e) {
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>\n";
}

// Tampilkan output
$output = ob_get_contents();
ob_end_clean();

echo $output;
?>