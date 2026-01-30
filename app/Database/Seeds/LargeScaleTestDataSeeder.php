<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LargeScaleTestDataSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama jika ada
        // Disable FK checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('anomali_log')->truncate();
        $this->db->table('dokumen_survei')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        // Ambil ID kegiatan yang valid
        $kegiatanIds = $this->db->table('master_kegiatan')->select('id_kegiatan')->get()->getResultArray();
        $kegiatanIds = array_column($kegiatanIds, 'id_kegiatan');
        if (empty($kegiatanIds))
            $kegiatanIds = [1];

        // Ambil ID petugas PCL (Role 3) dan Processing (Role 4)
        $pclIds = $this->db->table('users')->select('id_user')->where('id_role', 3)->get()->getResultArray();
        $pclIds = array_column($pclIds, 'id_user');
        if (empty($pclIds))
            $pclIds = [1];

        // Ambil ID petugas Processing (Role 4)
        $processorIds = $this->db->table('users')->select('id_user')->where('id_role', 4)->get()->getResultArray();
        $processorIds = array_column($processorIds, 'id_user');
        if (empty($processorIds))
            $processorIds = [1];

        // Generate 500 dokumen survei dummy untuk pengujian skala besar
        $dokumenData = [];
        for ($i = 1; $i <= 500; $i++) {
            $dokumenData[] = [
                'id_kegiatan' => $kegiatanIds[array_rand($kegiatanIds)],
                'kode_wilayah' => 'ID-' . sprintf('%03d', $i % 100), // Cycle 000-099
                'id_petugas_pendataan' => $pclIds[array_rand($pclIds)],
                'processed_by' => $processorIds[array_rand($processorIds)],
                'status' => ['Uploaded', 'Sudah Entry', 'Error', 'Valid'][array_rand(['Uploaded', 'Sudah Entry', 'Error', 'Valid'])],
                'pernah_error' => rand(0, 1),
                'tanggal_setor' => rand(0, 1) ? date('Y-m-d', strtotime('-' . rand(1, 30) . ' days')) : null
            ];

            // Insert setiap 100 record untuk menghindari kehabisan memori
            if ($i % 100 === 0 || $i === 500) {
                $this->db->table('dokumen_survei')->insertBatch($dokumenData);
                $dokumenData = []; // Reset array
            }
        }

        // Ambil ID dokumen yang baru saja dibuat untuk anomali log
        $dokumenIds = $this->db->table('dokumen_survei')->select('id_dokumen')->get()->getResultArray();
        $dokumenIds = array_column($dokumenIds, 'id_dokumen');

        if (empty($dokumenIds))
            return;

        // Generate 200 anomali log dummy
        $anomaliData = [];
        for ($i = 1; $i <= 200; $i++) {
            $anomaliData[] = [
                'id_dokumen' => $dokumenIds[array_rand($dokumenIds)],
                'id_petugas_pengolahan' => $processorIds[array_rand($processorIds)],
                'jenis_error' => [
                    'Format file tidak sesuai',
                    'Data tidak lengkap',
                    'Nilai tidak valid',
                    'Duplikasi entri',
                    'Tanggal tidak valid',
                    'Wilayah tidak ditemukan',
                    'Kode kegiatan salah',
                    'NIK tidak valid',
                    'Kesalahan input manual',
                    'Data melebihi batas wajar'
                ][array_rand([
                        'Format file tidak sesuai',
                        'Data tidak lengkap',
                        'Nilai tidak valid',
                        'Duplikasi entri',
                        'Tanggal tidak valid',
                        'Wilayah tidak ditemukan',
                        'Kode kegiatan salah',
                        'NIK tidak valid',
                        'Kesalahan input manual',
                        'Data melebihi batas wajar'
                    ])],
                'keterangan' => 'Anomali ditemukan dalam pengujian skala besar: ' . $i
            ];

            // Insert setiap 50 record untuk menghindari kehabisan memori
            if ($i % 50 === 0 || $i === 200) {
                $this->db->table('anomali_log')->insertBatch($anomaliData);
                $anomaliData = []; // Reset array
            }
        }
    }
}