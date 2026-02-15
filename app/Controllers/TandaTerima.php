<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NksModel;
use App\Models\TandaTerimaModel;

class TandaTerima extends BaseController
{
    protected $tandaTerimaModel;
    protected $nksModel;

    public function __construct()
    {
        $this->tandaTerimaModel = new TandaTerimaModel();
        $this->nksModel = new NksModel();
    }

    public function index()
    {
        // Mengambil data tanda terima + Join dengan NKS Master untuk dapat nama Desa/Kec
        // Catatan: Jika Model belum support join, kita pakai query builder manual di sini
        $db      = \Config\Database::connect();
        $builder = $db->table('tanda_terima');
        $builder->select('tanda_terima.*, nks_master.kecamatan, nks_master.desa');
        $builder->join('nks_master', 'nks_master.nks = tanda_terima.nks');
        $query   = $builder->get();

        $data = [
            'title' => 'Daftar Tanda Terima Dokumen',
            'data_terima' => $query->getResultArray()
        ];

        return view('tandaterima/index', $data);
    }

    public function new()
    {
        // Ambil semua NKS untuk Dropdown
        $data = [
            'title' => 'Input Penerimaan Dokumen',
            'data_nks' => $this->nksModel->findAll()
        ];

        return view('tandaterima/new', $data);
    }

    public function store()
    {
        // Validasi Input
        if (!$this->validate([
            'nks' => 'required',
            'jml_ruta_terima' => 'required|integer|less_than_equal_to[10]',
            'tgl_terima' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan ke Database
        $this->tandaTerimaModel->save([
            'nks' => $this->request->getPost('nks'),
            'jml_ruta_terima' => $this->request->getPost('jml_ruta_terima'),
            'tgl_terima' => $this->request->getPost('tgl_terima'),
        ]);

        // Redirect dengan pesan sukses
        session()->setFlashdata('pesan', 'Data berhasil disimpan.');
        return redirect()->to(base_url('tanda-terima'));
    }

    public function edit($id)
    {
        $dataTerima = $this->tandaTerimaModel->find($id);

        if (! $dataTerima) {
            session()->setFlashdata('pesan', 'Data tidak ditemukan.');
            return redirect()->to(base_url('tanda-terima'));
        }

        $data = [
            'title' => 'Edit Penerimaan Dokumen',
            'data_nks' => $this->nksModel->findAll(),
            'data_terima' => $dataTerima,
        ];

        return view('tandaterima/edit', $data);
    }

    public function update($id)
    {
        $dataTerima = $this->tandaTerimaModel->find($id);
        if (! $dataTerima) {
            session()->setFlashdata('pesan', 'Data tidak ditemukan.');
            return redirect()->to(base_url('tanda-terima'));
        }

        if (! $this->validate([
            'nks' => 'required',
            'jml_ruta_terima' => 'required|integer|less_than_equal_to[10]',
            'tgl_terima' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->tandaTerimaModel->update($id, [
            'nks' => $this->request->getPost('nks'),
            'jml_ruta_terima' => $this->request->getPost('jml_ruta_terima'),
            'tgl_terima' => $this->request->getPost('tgl_terima'),
        ]);

        session()->setFlashdata('pesan', 'Data berhasil diperbarui.');
        return redirect()->to(base_url('tanda-terima'));
    }

    public function delete($id)
    {
        $this->tandaTerimaModel->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
        return redirect()->to(base_url('tanda-terima'));
    }
}
