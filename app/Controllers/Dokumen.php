<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DokumenModel;
use App\Models\KegiatanModel;

class Dokumen extends BaseController
{
    protected $dokumenModel;
    protected $kegiatanModel;

    public function __construct()
    {
        $this->dokumenModel = new DokumenModel();
        $this->kegiatanModel = new KegiatanModel();
    }

    public function index()
    {
        $role_id = session()->get('id_role');
        $user_id = session()->get('id_user');

        $data = [
            'title' => 'Dokumen Survei',
            'dokumen' => $this->dokumenModel->getDokumenWithRelations($role_id, $user_id),
            'role_id' => $role_id
        ];

        return view('dokumen/index', $data);
    }

    public function create()
    {
        // Only PCL (Role 3) or Admin (Role 1) can create
        if (!in_array(session()->get('id_role'), [1, 3])) {
            return redirect()->to('/dokumen')->with('error', 'Akses ditolak.');
        }

        $data = [
            'title' => 'Setor Dokumen',
            'kegiatan' => $this->kegiatanModel->where('status', 'Aktif')->findAll()
        ];
        return view('dokumen/create', $data);
    }

    public function store()
    {
        if (!in_array(session()->get('id_role'), [1, 3])) {
            return redirect()->to('/dokumen');
        }

        $rules = [
            'id_kegiatan' => 'required',
            'kode_wilayah' => 'required',
            'tanggal_setor' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_kegiatan' => $this->request->getPost('id_kegiatan'),
            'kode_wilayah' => $this->request->getPost('kode_wilayah'),
            'tanggal_setor' => $this->request->getPost('tanggal_setor'),
            'id_petugas_pendataan' => session()->get('id_user'), // Auto-assign current user
            'status' => 'Uploaded'
        ];

        $this->dokumenModel->save($data);
        return redirect()->to('/dokumen')->with('success', 'Dokumen berhasil disetor.');
    }

    public function markEntry($id)
    {
        // Only Pengolahan (Role 4) or Admin (Role 1)
        if (!in_array(session()->get('id_role'), [1, 4])) {
            return redirect()->to('/dokumen')->with('error', 'Akses ditolak.');
        }

        $this->dokumenModel->update($id, [
            'status' => 'Sudah Entry',
            'processed_by' => session()->get('id_user')
        ]);

        return redirect()->to('/dokumen')->with('success', 'Dokumen ditandai sudah entry.');
    }

    public function reportError()
    {
        if (!in_array(session()->get('id_role'), [1, 4])) {
            return redirect()->to('/dokumen')->with('error', 'Akses ditolak.');
        }

        $rules = [
            'id_dokumen' => 'required|integer',
            'jenis_error' => 'required',
            'keterangan' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $id_dokumen = $this->request->getPost('id_dokumen');
        
        $anomaliModel = new \App\Models\AnomaliModel();
        
        // 1. Log Error
        $anomaliModel->save([
            'id_dokumen' => $id_dokumen,
            'id_petugas_pengolahan' => session()->get('id_user'),
            'jenis_error' => $this->request->getPost('jenis_error'),
            'keterangan' => $this->request->getPost('keterangan')
        ]);

        // 2. Update Document Status
        $this->dokumenModel->update($id_dokumen, [
            'status' => 'Error',
            'processed_by' => session()->get('id_user'),
            'pernah_error' => 1
        ]);

        return redirect()->to('/dokumen')->with('error', 'Anomali berhasil dilaporkan.');
    }
}
