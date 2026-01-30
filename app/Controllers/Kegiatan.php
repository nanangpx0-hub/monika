<?php

namespace App\Controllers;

use App\Controllers\BaseController;
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
            return redirect()->to('dashboard')->with('error', 'Akses ditolak.');
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
            return redirect()->to('dashboard');
        }

        // Validasi manual untuk field yang dikirim via form
        $rules = [
            'nama_kegiatan' => 'required|min_length[3]|max_length[100]',
            'tanggal_mulai' => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $namaKegiatan = $this->request->getPost('nama_kegiatan');
        
        // Auto-generate kode kegiatan
        $kodeKegiatan = $this->kegiatanModel->generateKode($namaKegiatan);

        $data = [
            'nama_kegiatan' => $namaKegiatan,
            'kode_kegiatan' => $kodeKegiatan,
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'status' => 'Aktif'
        ];

        // Gunakan insert() dengan skipValidation untuk bypass model validation
        if (!$this->kegiatanModel->skipValidation(true)->insert($data)) {
            return redirect()->back()->withInput()->with('errors', ['Gagal menyimpan data.']);
        }

        return redirect()->to('kegiatan')->with('success', 'Kegiatan berhasil ditambahkan dengan kode: ' . $kodeKegiatan);
    }

    public function updateStatus($id)
    {
        if (session()->get('id_role') != 1) {
            return redirect()->to('dashboard');
        }

        $status = $this->request->getPost('status'); // Aktif or Selesai

        // Use direct Query Builder to avoid CI4 Model type issues
        $db = \Config\Database::connect();
        $db->table('master_kegiatan')
            ->where('id_kegiatan', (int) $id)
            ->update(['status' => $status]);

        return redirect()->to('kegiatan')->with('success', 'Status kegiatan diperbarui.');
    }

    public function delete($id)
    {
        if (session()->get('id_role') != 1) {
            return redirect()->to('dashboard');
        }

        // Use direct Query Builder to avoid CI4 Model type issues
        $db = \Config\Database::connect();
        $db->table('master_kegiatan')
            ->where('id_kegiatan', (int) $id)
            ->delete();
            
        return redirect()->to('kegiatan')->with('success', 'Kegiatan dihapus.');
    }
}
