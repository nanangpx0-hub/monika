<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenModel extends Model
{
    protected $table            = 'dokumen_survei';
    protected $primaryKey       = 'id_dokumen';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_kegiatan', 'kode_wilayah', 'id_petugas_pendataan', 
        'processed_by', 'status', 'pernah_error', 'tanggal_setor'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_kegiatan' => 'required|integer',
        'kode_wilayah' => 'required|min_length[3]|max_length[20]',
        'id_petugas_pendataan' => 'required|integer',
        'tanggal_setor' => 'required|valid_date'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function getDokumenWithRelations($role_id, $user_id)
    {
        $builder = $this->db->table('dokumen_survei');
        $builder->select('dokumen_survei.*, master_kegiatan.nama_kegiatan, u_pcl.fullname as nama_pcl, u_proc.fullname as nama_pengolah');
        $builder->join('master_kegiatan', 'master_kegiatan.id_kegiatan = dokumen_survei.id_kegiatan');
        $builder->join('users as u_pcl', 'u_pcl.id_user = dokumen_survei.id_petugas_pendataan', 'left');
        $builder->join('users as u_proc', 'u_proc.id_user = dokumen_survei.processed_by', 'left');

        if ($role_id == 3) { // PCL only sees their own
            $builder->where('dokumen_survei.id_petugas_pendataan', $user_id);
        }

        $builder->orderBy('dokumen_survei.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getPclPerformance($id_kegiatan = null)
    {
        $builder = $this->db->table('users');
        $builder->select('users.fullname, users.nik_ktp, users.sobat_id, 
                          COUNT(dokumen_survei.id_dokumen) as total_dokumen,
                          SUM(CASE WHEN dokumen_survei.status = "Error" OR dokumen_survei.pernah_error = 1 THEN 1 ELSE 0 END) as total_error');
        $builder->join('dokumen_survei', 'dokumen_survei.id_petugas_pendataan = users.id_user', 'left');
        
        if ($id_kegiatan) {
            $builder->where('dokumen_survei.id_kegiatan', $id_kegiatan);
        }

        $builder->where('users.id_role', 3); // Role PCL
        $builder->groupBy('users.id_user');
        $builder->orderBy('total_dokumen', 'DESC'); // Default sort by productivity
        
        return $builder->get()->getResultArray();
    }

    public function getProcessorPerformance($id_kegiatan = null)
    {
        $builder = $this->db->table('users');
        $builder->select('users.fullname, users.sobat_id,
                          COUNT(dokumen_survei.id_dokumen) as total_entry');
        $builder->join('dokumen_survei', 'dokumen_survei.processed_by = users.id_user', 'left');

        if ($id_kegiatan) {
             $builder->where('dokumen_survei.id_kegiatan', $id_kegiatan);
        }

        $builder->where('users.id_role', 4); // Role Pengolahan
        $builder->groupBy('users.id_user');
        $builder->orderBy('total_entry', 'DESC');

        return $builder->get()->getResultArray();
    }
}
