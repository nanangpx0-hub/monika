<?php

namespace App\Models;

use CodeIgniter\Model;

class AnomaliModel extends Model
{
    protected $table            = 'anomali_log';
    protected $primaryKey       = 'id_anomali';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_dokumen', 'id_petugas_pengolahan', 'jenis_error', 'keterangan'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    protected $validationRules      = [
        'id_dokumen' => 'required|integer',
        'jenis_error' => 'required|max_length[100]',
        'keterangan' => 'required'
    ];
}
