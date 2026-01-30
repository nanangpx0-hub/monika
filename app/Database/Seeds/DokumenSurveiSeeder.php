<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DokumenSurveiSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama jika ada
        // Disable FK checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('dokumen_survei')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        // Ambil ID petugas berdasarkan role
        // Role 3: Petugas Pendataan (PCL)
        $pclIds = $this->db->table('users')->select('id_user')->where('id_role', 3)->get()->getResultArray();
        $pclIds = array_column($pclIds, 'id_user');

        // Role 4: Petugas Pengolahan
        $processorIds = $this->db->table('users')->select('id_user')->where('id_role', 4)->get()->getResultArray();
        $processorIds = array_column($processorIds, 'id_user');

        // Jika tidak ada user yang sesuai, gunakan admin (id 1) atau default
        if (empty($pclIds))
            $pclIds = [1];
        if (empty($processorIds))
            $processorIds = [1];

        // Ambil data kegiatan
        $kegiatanIds = $this->db->table('master_kegiatan')->select('id_kegiatan')->get()->getResultArray();
        $kegiatanIds = array_column($kegiatanIds, 'id_kegiatan');
        if (empty($kegiatanIds))
            $kegiatanIds = [1];

        // Data dummy dokumen survei
        $data = [];

        // Generate 50 dokumen survei dummy
        for ($i = 1; $i <= 50; $i++) {
            // Pilih acak kegiatan
            $id_kegiatan = $kegiatanIds[array_rand($kegiatanIds)];

            // Pilih acak petugas pendataan dari list PCL
            $id_petugas_pendataan = $pclIds[array_rand($pclIds)];

            // Pilih acak petugas pengolahan dari list Processor
            $processed_by = $processorIds[array_rand($processorIds)];

            // Status acak
            $status_list = ['Setor', 'Sudah Entry', 'Error', 'Valid'];
            $status = $status_list[array_rand($status_list)];

            // Tanggal setor acak
            $tanggal_setor = null;
            if ($status !== 'Setor') {
                $tanggal_setor = date('Y-m-d', strtotime('+' . rand(1, 30) . ' days'));
            }

            // Kode wilayah acak
            $kode_wilayah = 'ID-' . sprintf('%03d', $i);

            $data[] = [
                'id_kegiatan' => $id_kegiatan,
                'kode_wilayah' => $kode_wilayah,
                'id_petugas_pendataan' => $id_petugas_pendataan,
                'processed_by' => $processed_by,
                'status' => $status,
                'pernah_error' => rand(0, 1), // Boolean acak
                'tanggal_setor' => $tanggal_setor
            ];
        }

        // Insert data
        $this->db->table('dokumen_survei')->insertBatch($data);
    }
}