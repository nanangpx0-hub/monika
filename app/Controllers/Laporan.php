<?php

namespace App\Controllers;

use App\Models\DokumenModel;
use App\Models\KegiatanModel;

class Laporan extends BaseController
{
    protected $dokumenModel;
    protected $kegiatanModel;

    public function __construct()
    {
        $this->dokumenModel = new DokumenModel();
        $this->kegiatanModel = new KegiatanModel();
    }

    public function pcl()
    {
        if (session()->get('id_role') != 1) { // Admin only for now
            return redirect()->to('/dashboard');
        }

        $id_kegiatan = $this->request->getGet('kegiatan');

        $data = [
            'title' => 'Laporan Kinerja PCL',
            'list_kegiatan' => $this->kegiatanModel->findAll(), // All activities including closed
            'selected_kegiatan' => $id_kegiatan,
            'laporan' => $this->dokumenModel->getPclPerformance($id_kegiatan)
        ];

        return view('laporan/pcl', $data);
    }

    public function pengolahan()
    {
         if (session()->get('id_role') != 1) {
            return redirect()->to('/dashboard');
        }

        $id_kegiatan = $this->request->getGet('kegiatan');

        $data = [
            'title' => 'Laporan Kinerja Pengolahan',
            'list_kegiatan' => $this->kegiatanModel->findAll(),
            'selected_kegiatan' => $id_kegiatan,
            'laporan' => $this->dokumenModel->getProcessorPerformance($id_kegiatan)
        ];

        return view('laporan/pengolahan', $data);
    }
}
