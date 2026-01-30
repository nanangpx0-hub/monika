<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // Jalankan seeder dalam urutan yang benar berdasarkan relasi
        $this->call('RolesSeeder');
        $this->call('MasterKegiatanSeeder');
        $this->call('UsersSeeder');
        $this->call('DokumenSurveiSeeder');
        $this->call('AnomaliLogSeeder');
    }
}