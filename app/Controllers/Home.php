<?php

namespace App\Controllers;

class Home extends BaseController
{
    protected $dokumenModel;
    protected $kegiatanModel;
    protected $db;

    public function __construct()
    {
        $this->dokumenModel = new \App\Models\DokumenModel();
        $this->kegiatanModel = new \App\Models\KegiatanModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $role_id = session()->get('id_role');
        $user_id = session()->get('id_user');
        $filter_kegiatan = $this->request->getGet('kegiatan');

        // Stats Base Query - Create separate builders to avoid clone issues
        $baseConditions = function($builder) use ($filter_kegiatan) {
            if ($filter_kegiatan) {
                $builder->where('id_kegiatan', $filter_kegiatan);
            }
            return $builder;
        };

        // Ranking Query (Top Error Contributors)
        $rankingBuilder = $this->db->table('dokumen_survei');
        $rankingBuilder->select('users.fullname, COUNT(dokumen_survei.id_dokumen) as error_count');
        $rankingBuilder->join('users', 'users.id_user = dokumen_survei.id_petugas_pendataan');
        $rankingBuilder->where('dokumen_survei.status', 'Error'); // Or use pernah_error if tracking history
        
        if ($filter_kegiatan) {
            $rankingBuilder->where('dokumen_survei.id_kegiatan', $filter_kegiatan);
        }

        $rankingBuilder->groupBy('dokumen_survei.id_petugas_pendataan');
        $rankingBuilder->orderBy('error_count', 'DESC');
        $rankingBuilder->limit(5);

        $data = [
            'title' => 'Dashboard',
            'list_kegiatan' => $this->kegiatanModel->where('status', 'Aktif')->findAll(),
            'selected_kegiatan' => $filter_kegiatan,
            'stat_total' => $baseConditions($this->db->table('dokumen_survei'))->countAllResults(),
            'stat_error' => $baseConditions($this->db->table('dokumen_survei'))->where('status', 'Error')->countAllResults(),
            'stat_entry' => $baseConditions($this->db->table('dokumen_survei'))->where('status', 'Sudah Entry')->countAllResults(),
            'ranking' => $rankingBuilder->get()->getResultArray()
        ];

        return view('dashboard/index', $data);
    }
}
