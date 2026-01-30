<?php
// repro_kegiatan.php

// Define paths
define('FCPATH', __DIR__ . '/public/');
define('ROOTPATH', __DIR__ . '/');
define('APPPATH', __DIR__ . '/app/');
define('SYSTEMPATH', __DIR__ . '/vendor/codeigniter4/framework/system/');

// Load environment
define('ENVIRONMENT', 'development');
require_once SYSTEMPATH . 'CodeIgniter.php';
require_once APPPATH . 'Config/Paths.php';

// Bootstrap
$paths = new Config\Paths();
// Ensure paths are correct
$paths->systemDirectory = SYSTEMPATH;
$paths->appDirectory = APPPATH;
$paths->writableDirectory = ROOTPATH . 'writable';
$paths->testsDirectory = ROOTPATH . 'tests';

require_once SYSTEMPATH . 'Boot.php';
CodeIgniter\Boot::bootConsole($paths);

// Mock Request (Not really needed for model insert if we pass data manually)

// Instantiate Model
$model = new \App\Models\KegiatanModel();

// Prepare Data
$namaKegiatan = 'Test Kegiatan';
$kodeKegiatan = 'TES_2602_001'; // Dummy

$data = [
    'nama_kegiatan' => $namaKegiatan,
    'kode_kegiatan' => $kodeKegiatan,
    'tanggal_mulai' => '2026-02-01',
    'tanggal_selesai' => '2026-02-28',
    'status' => 'Aktif'
];

echo "Data keys: " . implode(', ', array_keys($data)) . "\n";
foreach ($data as $k => $v) {
    echo "Key type: " . gettype($k) . " ($k)\n";
}

echo "Attempting insert...\n";

try {
    $model->skipValidation(true)->insert($data);
    echo "Insert success!\n";
} catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
