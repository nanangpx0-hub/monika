<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanModel extends Model
{
    protected $table            = 'master_kegiatan';
    protected $primaryKey       = 'id_kegiatan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_kegiatan', 'kode_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Table only has created_at, no updated_at column
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nama_kegiatan' => 'required|min_length[3]|max_length[100]',
        'kode_kegiatan' => 'required|min_length[3]|max_length[20]|is_unique[master_kegiatan.kode_kegiatan,id_kegiatan,{id_kegiatan}]',
        'tanggal_mulai' => 'required|valid_date',
        'tanggal_selesai' => 'required|valid_date',
        'status'        => 'in_list[Aktif,Selesai]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Generate kode kegiatan unik secara otomatis
     * Format: [3 huruf pertama nama]_[YYMM]_[3 digit angka acak]
     * Contoh: SUS_2601_032
     */
    public function generateKode(?string $namaKegiatan = null): string
    {
        // Handle null atau empty string
        if (empty($namaKegiatan)) {
            $namaKegiatan = 'KEG'; // Default prefix jika nama kosong
        }

        // Ambil 3 huruf pertama dari nama kegiatan
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $namaKegiatan), 0, 3));
        if (strlen($prefix) < 3) {
            $prefix = str_pad($prefix, 3, 'X');
        }

        // Ambil tahun dan bulan sekarang (YYMM)
        $datePart = date('ym');

        // Buat 3 digit angka acak
        $randomPart = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);

        $kode = $prefix . '_' . $datePart . '_' . $randomPart;

        // Pastikan kode unik
        while ($this->where('kode_kegiatan', $kode)->first()) {
            $randomPart = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
            $kode = $prefix . '_' . $datePart . '_' . $randomPart;
        }

        return $kode;
    }
}
