<?php

namespace App\Models;

use CodeIgniter\Model;

class UjiPetikModel extends Model
{
    protected $table            = 'uji_petik';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nks',
        'no_ruta',
        'variabel',
        'isian_k',
        'isian_c',
        'alasan_kesalahan',
        'catatan'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation Rules
    protected $validationRules = [
        'nks'              => 'required|max_length[10]',
        'no_ruta'          => 'required|integer|greater_than[0]|less_than_equal_to[10]',
        'variabel'         => 'required|max_length[100]',
        'isian_k'          => 'required|max_length[255]',
        'isian_c'          => 'required|max_length[255]',
        'alasan_kesalahan' => 'required|in_list[Salah Ketik,Salah Baca,Terlewat,Salah Kode,Lainnya]',
    ];

    protected $validationMessages = [
        'no_ruta' => [
            'less_than_equal_to' => 'Nomor ruta maksimal 10',
        ],
    ];

    /**
     * Get all uji petik data with NKS details
     */
    public function getAllWithNks()
    {
        $builder = $this->db->table($this->table);
        $builder->select('uji_petik.*, nks_master.kecamatan, nks_master.desa');
        $builder->join('nks_master', 'nks_master.nks = uji_petik.nks', 'left');
        $builder->orderBy('uji_petik.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }
}
