<?php

namespace App\Controllers;

use App\Models\KegiatanModel;

class Kegiatan extends BaseController
{
    protected $kegiatanModel;

    public function __construct()
    {
        $this->kegiatanModel = new KegiatanModel();
    }

    public function index()
    {
        // Check if user is Admin (Role 1)
        if (session()->get('id_role') != 1) {
             return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $data = [
            'title' => 'Master Kegiatan',
            'kegiatan' => $this->kegiatanModel->findAll()
        ];
        return view('kegiatan/index', $data);
    }

    public function store()
    {
        if (session()->get('id_role') != 1) {
            return redirect()->to('/dashboard');
        }

        $rules = [
            'nama_kegiatan'  => 'required|min_length[3]|max_length[100]',
            'kode_kegiatan'  => 'required|min_length[3]|max_length[20]',
            'tanggal_mulai'  => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_kegiatan' => $this->request->getPost('nama_kegiatan'),
            'kode_kegiatan' => strtoupper($this->request->getPost('kode_kegiatan')),
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'status' => 'Aktif'
        ];

        if (!$this->kegiatanModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->kegiatanModel->errors());
        }

        return redirect()->to('/kegiatan')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function updateStatus($id)
    {
        if (session()->get('id_role') != 1) {
            return redirect()->to('/dashboard');
        }

        $status = $this->request->getPost('status'); // Aktif or Selesai
        $this->kegiatanModel->update($id, ['status' => $status]);
        
        return redirect()->to('/kegiatan')->with('success', 'Status kegiatan diperbarui.');
    }

    public function delete($id)
    {
        if (session()->get('id_role') != 1) {
            return redirect()->to('/dashboard');
        }

        $this->kegiatanModel->delete($id);
        return redirect()->to('/kegiatan')->with('success', 'Kegiatan dihapus.');
    }
}
