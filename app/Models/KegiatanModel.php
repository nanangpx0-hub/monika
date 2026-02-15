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
    protected $updatedField  = ''; // No updated_at in schema for this table, or use same if alters made. Schema says created_at only.
    // Wait, schema has created_at only.

    // Validation
    protected $validationRules      = [
        'nama_kegiatan' => 'required|min_length(3)|max_length(100)',
        'kode_kegiatan' => 'required|min_length(3)|max_length(20)|is_unique[master_kegiatan.kode_kegiatan,id_kegiatan,{id_kegiatan}]',
        'tanggal_mulai' => 'required|valid_date',
        'tanggal_selesai' => 'required|valid_date',
        'status'        => 'in_list[Aktif,Selesai]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
