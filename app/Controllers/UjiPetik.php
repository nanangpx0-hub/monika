<?php

namespace App\Controllers;

use App\Models\UjiPetikModel;
use App\Models\NksModel;

class UjiPetik extends BaseController
{
    protected $ujiPetikModel;
    protected $nksModel;

    public function __construct()
    {
        $this->ujiPetikModel = new UjiPetikModel();
        $this->nksModel = new NksModel();
    }

    /**
     * Display list of all uji petik findings
     */
    public function index()
    {
        $data = [
            'title' => 'Uji Petik Kualitas',
            'findings' => $this->ujiPetikModel->getAllWithNks()
        ];

        return view('uji_petik/index', $data);
    }

    /**
     * Show form to create new uji petik finding
     */
    public function new()
    {
        $data = [
            'title' => 'Tambah Temuan Uji Petik',
            'nks_list' => $this->nksModel->findAll(),
            'alasan_list' => ['Salah Ketik', 'Salah Baca', 'Terlewat', 'Salah Kode', 'Lainnya']
        ];

        return view('uji_petik/new', $data);
    }

    /**
     * Show form to edit existing uji petik finding
     */
    public function edit($id)
    {
        $finding = $this->ujiPetikModel->find($id);
        if (! $finding) {
            return redirect()->to('/uji-petik')->with('error', 'Temuan tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Temuan Uji Petik',
            'finding' => $finding,
            'nks_list' => $this->nksModel->findAll(),
            'alasan_list' => ['Salah Ketik', 'Salah Baca', 'Terlewat', 'Salah Kode', 'Lainnya'],
        ];

        return view('uji_petik/edit', $data);
    }

    /**
     * Store new uji petik finding
     */
    public function store()
    {
        $rules = [
            'nks'              => 'required',
            'no_ruta'          => 'required|integer|greater_than[0]|less_than_equal_to[10]',
            'variabel'         => 'required',
            'isian_k'          => 'required',
            'isian_c'          => 'required',
            'alasan_kesalahan' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nks'              => $this->request->getPost('nks'),
            'no_ruta'          => $this->request->getPost('no_ruta'),
            'variabel'         => $this->request->getPost('variabel'),
            'isian_k'          => $this->request->getPost('isian_k'),
            'isian_c'          => $this->request->getPost('isian_c'),
            'alasan_kesalahan' => $this->request->getPost('alasan_kesalahan'),
            'catatan'          => $this->request->getPost('catatan'),
        ];

        if ($this->ujiPetikModel->insert($data)) {
            return redirect()->to('/uji-petik')->with('success', 'Temuan berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data');
    }

    /**
     * Update existing uji petik finding
     */
    public function update($id)
    {
        $finding = $this->ujiPetikModel->find($id);
        if (! $finding) {
            return redirect()->to('/uji-petik')->with('error', 'Temuan tidak ditemukan.');
        }

        $rules = [
            'nks'              => 'required',
            'no_ruta'          => 'required|integer|greater_than[0]|less_than_equal_to[10]',
            'variabel'         => 'required',
            'isian_k'          => 'required',
            'isian_c'          => 'required',
            'alasan_kesalahan' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nks'              => $this->request->getPost('nks'),
            'no_ruta'          => $this->request->getPost('no_ruta'),
            'variabel'         => $this->request->getPost('variabel'),
            'isian_k'          => $this->request->getPost('isian_k'),
            'isian_c'          => $this->request->getPost('isian_c'),
            'alasan_kesalahan' => $this->request->getPost('alasan_kesalahan'),
            'catatan'          => $this->request->getPost('catatan'),
        ];

        if ($this->ujiPetikModel->update($id, $data)) {
            return redirect()->to('/uji-petik')->with('success', 'Temuan berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data');
    }

    /**
     * Delete uji petik finding
     */
    public function delete($id)
    {
        if ($this->ujiPetikModel->delete($id)) {
            return redirect()->to('/uji-petik')->with('success', 'Temuan berhasil dihapus');
        }

        return redirect()->to('/uji-petik')->with('error', 'Gagal menghapus data');
    }
}
