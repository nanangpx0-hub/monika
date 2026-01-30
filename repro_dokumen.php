<?php

// Define CodeIgniter constants
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(__DIR__);

// Load CodeIgniter
require 'vendor/codeigniter4/framework/system/bootstrap.php';

use App\Models\DokumenModel;
use CodeIgniter\Config\Services;

// Mock the database connection
$db = \CodeIgniter\Database\Config::connect();

$model = new DokumenModel();

// Data to insert (valid data to pass other rules)
$data = [
    'id_kegiatan' => 1,
    'kode_wilayah' => '12345', // valid length
    'id_petugas_pendataan' => 1,
    'tanggal_setor' => '2023-10-27',
    'status' => 'Uploaded'
];

echo "Attempting validation...\n";

try {
    // We can just call validate() directly to trigger rule parsing
    if (!$model->validate($data)) {
        echo "Validation failed (expected if rules are parsed but data is invalid, but here we expect exception):\n";
        print_r($model->errors());
    } else {
        echo "Validation passed!\n";
    }
} catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
