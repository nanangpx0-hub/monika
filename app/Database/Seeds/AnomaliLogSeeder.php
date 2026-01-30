<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnomaliLogSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama jika ada
        $this->db->table('anomali_log')->truncate();

        // Ambil ID dokumen yang ada
        $dokumenIds = $this->db->table('dokumen_survei')->select('id_dokumen')->get()->getResultArray();
        $dokumenIds = array_column($dokumenIds, 'id_dokumen');

        // Ambil ID petugas pengolahan (Role 4)
        $processorIds = $this->db->table('users')->select('id_user')->where('id_role', 4)->get()->getResultArray();
        $processorIds = array_column($processorIds, 'id_user');

        // Jika data kosong, gunakan default untuk menghindari error
        if (empty($dokumenIds))
            return; // Tidak bisa buat log anomali jika tidak ada dokumen
        if (empty($processorIds))
            $processorIds = [1]; // Fallback ke admin

        // Data dummy anomali log
        $data = [];

        // Generate 30 anomali log dummy (atau lebih sedikit jika dokumen < 30)
        $jumlahLog = min(30, count($dokumenIds));

        for ($i = 1; $i <= $jumlahLog; $i++) {
            // Pilih acak dokumen dari yang tersedia
            $id_dokumen = $dokumenIds[array_rand($dokumenIds)];

            // Pilih acak petugas pengolahan
            $id_petugas_pengolahan = $processorIds[array_rand($processorIds)];

            // Jenis error acak
            $jenis_errors = [
                'Format file tidak sesuai',
                'Data tidak lengkap',
                'Nilai tidak valid',
                'Duplikasi entri',
                'Tanggal tidak valid',
                'Wilayah tidak ditemukan',
                'Kode kegiatan salah',
                'NIK tidak valid'
            ];
            $jenis_error = $jenis_errors[array_rand($jenis_errors)];

            // Keterangan acak
            $keterangan_list = [
                'Ditemukan kesalahan pada field nomor rumah tangga',
                'Nilai pada kolom pendapatan tidak sesuai format',
                'Terdapat data ganda untuk kode wilayah yang sama',
                'Tanggal pelaksanaan survei tidak sesuai dengan jadwal kegiatan',
                'Format dokumen tidak sesuai standar yang ditentukan',
                'Kesalahan dalam pengisian data demografi',
                'Nilai ekstrem ditemukan pada variabel pengeluaran',
                'Kode wilayah tidak terdaftar dalam referensi'
            ];
            $keterangan = $keterangan_list[array_rand($keterangan_list)];

            $data[] = [
                'id_dokumen' => $id_dokumen,
                'id_petugas_pengolahan' => $id_petugas_pengolahan,
                'jenis_error' => $jenis_error,
                'keterangan' => $keterangan
            ];
        }

        // Insert data
        if (!empty($data)) {
            $this->db->table('anomali_log')->insertBatch($data);
        }
    }
}