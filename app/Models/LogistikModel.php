<?php

namespace App\Models;

use CodeIgniter\Model;

class LogistikModel extends Model
{
    protected $table            = 'logistik';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'satuan',
        'stok',
        'kondisi',
        'lokasi',
        'keterangan',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
