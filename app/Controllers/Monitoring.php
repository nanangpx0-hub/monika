<?php

namespace App\Controllers;

use App\Models\DokumenModel;
use App\Models\KegiatanModel;

class Monitoring extends BaseController
{
    protected $dokumenModel;
    protected $kegiatanModel;
    protected $db;

    public function __construct()
    {
        $this->dokumenModel = new DokumenModel();
        $this->kegiatanModel = new KegiatanModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $filter_kegiatan = $this->request->getGet('kegiatan');

        // 1. Stats Widgets
        $builder = $this->db->table('dokumen_survei');
        if ($filter_kegiatan) {
            $builder->where('id_kegiatan', $filter_kegiatan);
        }
        
        $totalDocs = clone $builder;
        $totalEntry = clone $builder;
        $totalError = clone $builder;
        $totalClean = clone $builder; // Docs with no error and entered

        // 2. Ranking (Top Error Contributors)
        $rankingBuilder = $this->db->table('dokumen_survei');
        $rankingBuilder->select('users.fullname, COUNT(dokumen_survei.id_dokumen) as error_count');
        $rankingBuilder->join('users', 'users.id_user = dokumen_survei.id_petugas_pendataan');
        $rankingBuilder->where('dokumen_survei.status', 'Error');
        if ($filter_kegiatan) {
            $rankingBuilder->where('dokumen_survei.id_kegiatan', $filter_kegiatan);
        }
        $rankingBuilder->groupBy('dokumen_survei.id_petugas_pendataan');
        $rankingBuilder->orderBy('error_count', 'DESC');
        $rankingBuilder->limit(5);

        // 3. Evaluation Tables
        // PCL (Quality)
        $pclPerf = $this->dokumenModel->getPclPerformance($filter_kegiatan);

        // Processor (Productivity)
        $procPerf = $this->dokumenModel->getProcessorPerformance($filter_kegiatan);

        // Supervisor (Team Aggregate) - Logic here or in model. Let's do inline for specific request.
        // Get PML (Role 5) and aggregate their PCLs (Role 3)
        $spvBuilder = $this->db->table('users as spv');
        $spvBuilder->select('spv.fullname, COUNT(DISTINCT pcl.id_user) as team_size, 
                             COUNT(doc.id_dokumen) as total_team_docs,
                             SUM(CASE WHEN doc.status = "Error" OR doc.pernah_error = 1 THEN 1 ELSE 0 END) as total_team_errors');
        $spvBuilder->join('users as pcl', 'pcl.id_supervisor = spv.id_user', 'left');
        $spvBuilder->join('dokumen_survei as doc', 'doc.id_petugas_pendataan = pcl.id_user', 'left');
        
        if ($filter_kegiatan) {
            $spvBuilder->where('doc.id_kegiatan', $filter_kegiatan);
        }
        
        $spvBuilder->where('spv.id_role', 5); // PML
        $spvBuilder->groupBy('spv.id_user');
        $supervisorPerf = $spvBuilder->get()->getResultArray();

        $data = [
            'title' => 'Monitoring & Evaluasi',
            'list_kegiatan' => $this->kegiatanModel->where('status', 'Aktif')->findAll(),
            'selected_kegiatan' => $filter_kegiatan,
            
            // Stats
            'stat_total' => $totalDocs->countAllResults(),
            'stat_entry' => $totalEntry->where('status', 'Sudah Entry')->countAllResults(),
            'stat_error' => $totalError->where('status', 'Error')->countAllResults(),
            'stat_clean' => $totalClean->where('status', 'Sudah Entry')->where('pernah_error', 0)->countAllResults(),
            
            // Tables
            'ranking_error' => $rankingBuilder->get()->getResultArray(),
            'eval_pcl' => $pclPerf,
            'eval_proc' => $procPerf,
            'eval_spv' => $supervisorPerf
        ];

        return view('monitoring/dashboard', $data);
    }
}
