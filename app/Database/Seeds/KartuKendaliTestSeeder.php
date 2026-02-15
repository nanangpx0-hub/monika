<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KartuKendaliTestSeeder extends Seeder
{
    public function run()
    {
        // Insert sample NKS data
        $nksData = [
            ['nks' => '26001', 'kd_bs' => '001', 'kecamatan' => 'Kencong', 'desa' => 'Kencong', 'target_ruta' => 10],
            ['nks' => '26002', 'kd_bs' => '002', 'kecamatan' => 'Kencong', 'desa' => 'Paseban', 'target_ruta' => 10],
            ['nks' => '26003', 'kd_bs' => '003', 'kecamatan' => 'Gumukmas', 'desa' => 'Karangharjo', 'target_ruta' => 10],
        ];

        foreach ($nksData as $nks) {
            $existing = $this->db->table('nks_master')->where('nks', $nks['nks'])->get()->getRowArray();
            if (!$existing) {
                $this->db->table('nks_master')->insert($nks);
            }
        }

        // Insert tanda terima (documents received)
        $tandaTerimaData = [
            ['nks' => '26001', 'jml_ruta_terima' => 10, 'tgl_terima' => date('Y-m-d')],
            ['nks' => '26002', 'jml_ruta_terima' => 6, 'tgl_terima' => date('Y-m-d')],
            ['nks' => '26003', 'jml_ruta_terima' => 4, 'tgl_terima' => date('Y-m-d')],
        ];

        foreach ($tandaTerimaData as $terima) {
            $existing = $this->db->table('tanda_terima')->where('nks', $terima['nks'])->get()->getRowArray();
            if (!$existing) {
                $this->db->table('tanda_terima')->insert($terima);
            }
        }

        echo "Sample data for Kartu Kendali created successfully!\n";
    }
}
