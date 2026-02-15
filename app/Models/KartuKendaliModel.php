<?php

namespace App\Models;

use CodeIgniter\Model;

class KartuKendaliModel extends Model
{
    protected $table            = 'kartu_kendali';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nks', 
        'no_ruta', 
        'user_id', 
        'status_entry', 
        'is_patch_issue', 
        'tgl_entry'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation Rules
    protected $validationRules = [
        'nks'          => 'required|max_length[10]',
        'no_ruta'      => 'required|integer|greater_than[0]|less_than_equal_to[10]',
        'user_id'      => 'required|integer',
        'status_entry' => 'required|in_list[Clean,Error]',
        'tgl_entry'    => 'required|valid_date',
    ];

    protected $validationMessages = [
        'no_ruta' => [
            'less_than_equal_to' => 'Nomor ruta maksimal 10',
        ],
    ];

    /**
     * Get progress summary for all NKS
     * Using subquery approach to avoid GROUP BY issues with only_full_group_by
     */
    public function getProgressByNks()
    {
        $db = \Config\Database::connect();

        // 1. Subquery: Hitung total dokumen fisik yang diterima (SUM)
        // Dikelompokkan per NKS agar tidak error group by
        $subQueryTerima = $db->table('tanda_terima')
            ->select('nks, SUM(jml_ruta_terima) as total_fisik_masuk')
            ->groupBy('nks')
            ->getCompiledSelect();

        // 2. Subquery: Hitung total yang sudah di-entry (Clean/Error)
        $subQueryEntry = $db->table('kartu_kendali')
            ->select('nks, COUNT(id) as total_entry_selesai')
            ->whereIn('status_entry', ['Clean', 'Error'])
            ->groupBy('nks')
            ->getCompiledSelect();

        // 3. Main Query: Join Master NKS dengan Subquery
        $builder = $this->db->table('nks_master');
        $builder->select('nks_master.nks, 
                          nks_master.kecamatan, 
                          nks_master.desa, 
                          nks_master.target_ruta,
                          COALESCE(terima.total_fisik_masuk, 0) as jml_terima,
                          COALESCE(entry.total_entry_selesai, 0) as jml_selesai');

        // Join Subquery (Alias 'terima' dan 'entry')
        $builder->join("($subQueryTerima) as terima", 'terima.nks = nks_master.nks', 'left');
        $builder->join("($subQueryEntry) as entry", 'entry.nks = nks_master.nks', 'left');

        $builder->orderBy('nks_master.nks', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get all entries for specific NKS
     */
    public function getEntriesByNks($nks)
    {
        $builder = $this->db->table($this->table);
        $builder->select('kartu_kendali.*, users.fullname, users.nama');
        
        // Handle both 'fullname' and 'nama' columns
        $userPk = $this->db->fieldExists('id_user', 'users') ? 'id_user' : 'id';
        $builder->join('users', "users.{$userPk} = kartu_kendali.user_id", 'left');
        $builder->where('kartu_kendali.nks', $nks);
        $builder->orderBy('kartu_kendali.no_ruta', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Check if ruta is already taken by another user
     */
    public function isRutaTaken($nks, $no_ruta, $excludeUserId = null)
    {
        $builder = $this->where('nks', $nks)->where('no_ruta', $no_ruta);
        
        if ($excludeUserId !== null) {
            $builder->where('user_id !=', $excludeUserId);
        }
        
        return $builder->countAllResults() > 0;
    }
}
