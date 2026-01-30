<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterKegiatanSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama jika ada
        // Disable FK checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('master_kegiatan')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        // Data dummy kegiatan
        $data = [
            [
                'nama_kegiatan' => 'Sakernas Februari 2026',
                'kode_kegiatan' => 'SAK26FEB',
                'tanggal_mulai' => '2026-02-01',
                'tanggal_selesai' => '2026-02-28',
                'status' => 'Aktif'
            ],
            [
                'nama_kegiatan' => 'Susenas Maret 2026',
                'kode_kegiatan' => 'SSN26MAR',
                'tanggal_mulai' => '2026-03-01',
                'tanggal_selesai' => '2026-03-31',
                'status' => 'Aktif'
            ],
            [
                'nama_kegiatan' => 'Pemutakhiran Registrasi Sosial Ekonomi (RSE) April 2026',
                'kode_kegiatan' => 'RSE26APR',
                'tanggal_mulai' => '2026-04-01',
                'tanggal_selesai' => '2026-04-30',
                'status' => 'Aktif'
            ],
            [
                'nama_kegiatan' => 'Survei Triwulanan Angkatan Kerja Nasional (Sakernas) Juni 2026',
                'kode_kegiatan' => 'STAKERNAS26JUN',
                'tanggal_mulai' => '2026-06-01',
                'tanggal_selesai' => '2026-06-30',
                'status' => 'Aktif'
            ],
            [
                'nama_kegiatan' => 'Survei Sosial Ekonomi Nasional (Susenas) September 2026',
                'kode_kegiatan' => 'SSN26SEP',
                'tanggal_mulai' => '2026-09-01',
                'tanggal_selesai' => '2026-09-30',
                'status' => 'Aktif'
            ]
        ];

        // Insert data
        $this->db->table('master_kegiatan')->insertBatch($data);
    }
}