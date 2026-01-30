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

        $rawData = [
            'id_kegiatan' => $this->request->getPost('id_kegiatan'),
            'kode_wilayah' => $this->request->getPost('kode_wilayah'),
            'tanggal_setor' => $this->request->getPost('tanggal_setor')
        ];

        // Bersihkan data input sebelum diproses
        $cleanedData = $this->cleanData($rawData);

        $data = array_merge($cleanedData, [
            'id_petugas_pendataan' => session()->get('id_user'), // Auto-assign current user
            'status' => 'Setor'
        ]);

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

    public function markClean($id)
    {
        // Only Pengolahan (Role 4) or Admin (Role 1)
        if (!in_array(session()->get('id_role'), [1, 4])) {
            return redirect()->to('/dokumen')->with('error', 'Akses ditolak.');
        }

        $this->dokumenModel->update($id, [
            'status' => 'Valid',
            'processed_by' => session()->get('id_user')
        ]);

        return redirect()->to('/dokumen')->with('success', 'Dokumen ditandai clean/valid.');
    }

    public function edit($id)
    {
        // Only Pengolahan (Role 4) or Admin (Role 1)
        if (!in_array(session()->get('id_role'), [1, 4])) {
            return redirect()->to('/dokumen')->with('error', 'Akses ditolak.');
        }

        $dokumen = $this->dokumenModel->find($id);
        if (!$dokumen) {
            return redirect()->to('/dokumen')->with('error', 'Dokumen tidak ditemukan.');
        }

        // Admin can edit any status, Pengolahan only Error or Valid
        if (session()->get('id_role') != 1 && !in_array($dokumen['status'], ['Error', 'Valid', 'Sudah Entry'])) {
             return redirect()->to('/dokumen')->with('error', 'Hanya dokumen dengan status Error, Valid, atau Sudah Entry yang dapat diedit.');
        }

        $data = [
            'title' => 'Edit Dokumen',
            'dokumen' => $dokumen,
            'kegiatan' => $this->kegiatanModel->where('status', 'Aktif')->findAll()
        ];

        return view('dokumen/edit', $data);
    }

    public function delete($id)
    {
        // Only Admin (Role 1) can delete
        if (session()->get('id_role') != 1) {
            return redirect()->to('/dokumen')->with('error', 'Akses ditolak. Hanya Admin yang dapat menghapus dokumen.');
        }

        $dokumen = $this->dokumenModel->find($id);
        if (!$dokumen) {
            return redirect()->to('/dokumen')->with('error', 'Dokumen tidak ditemukan.');
        }

        // Delete related anomali logs first
        $anomaliModel = new \App\Models\AnomaliModel();
        $anomaliModel->where('id_dokumen', $id)->delete();

        // Delete the document
        $this->dokumenModel->delete($id);

        // Audit log
        $audit = new \App\Models\AuditModel();
        $audit->log('Dokumen Deleted', "Dokumen ID $id dihapus oleh Admin.", session()->get('id_user'));

        return redirect()->to('/dokumen')->with('success', 'Dokumen berhasil dihapus.');
    }

    public function resetStatus($id)
    {
        // Only Admin (Role 1) can reset status
        if (session()->get('id_role') != 1) {
            return redirect()->to('/dokumen')->with('error', 'Akses ditolak. Hanya Admin yang dapat mereset status.');
        }

        $dokumen = $this->dokumenModel->find($id);
        if (!$dokumen) {
            return redirect()->to('/dokumen')->with('error', 'Dokumen tidak ditemukan.');
        }

        $oldStatus = $dokumen['status'];

        // Reset status to blank (null)
        $this->dokumenModel->update($id, [
            'status' => null,
            'processed_by' => null,
            'pernah_error' => 0
        ]);

        // Audit log
        $audit = new \App\Models\AuditModel();
        $audit->log('Dokumen Status Reset', "Dokumen ID $id status direset dari '$oldStatus' ke kosong.", session()->get('id_user'));

        return redirect()->to('/dokumen')->with('success', 'Status dokumen berhasil direset menjadi kosong.');
    }

    public function update($id)
    {
        // Only Pengolahan (Role 4) or Admin (Role 1)
        if (!in_array(session()->get('id_role'), [1, 4])) {
            return redirect()->to('/dokumen')->with('error', 'Akses ditolak.');
        }

        $rules = [
            'id_kegiatan' => 'required',
            'kode_wilayah' => 'required',
            'tanggal_setor' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $rawData = [
            'id_kegiatan' => $this->request->getPost('id_kegiatan'),
            'kode_wilayah' => $this->request->getPost('kode_wilayah'),
            'tanggal_setor' => $this->request->getPost('tanggal_setor')
        ];

        // Bersihkan data input sebelum diproses
        $cleanedData = $this->cleanData($rawData);

        $data = array_merge($cleanedData, [
            'status' => 'Setor', // Reset status to Setor after fix
            'processed_by' => session()->get('id_user'),
            'pernah_error' => 1 // Keep track that it was once errored (optional, but good for audit)
        ]);

        $this->dokumenModel->update($id, $data);
        return redirect()->to('/dokumen')->with('success', 'Dokumen berhasil diperbaiki.');
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
        
        // Data input untuk anomali
        $rawAnomaliData = [
            'jenis_error' => $this->request->getPost('jenis_error'),
            'keterangan' => $this->request->getPost('keterangan')
        ];

        // Bersihkan data
        $cleanedAnomali = $this->cleanData($rawAnomaliData);

        // 1. Log Error
        $anomaliModel->save([
            'id_dokumen' => $id_dokumen,
            'id_petugas_pengolahan' => session()->get('id_user'),
            'jenis_error' => $cleanedAnomali['jenis_error'],
            'keterangan' => $cleanedAnomali['keterangan']
        ]);

        // 2. Update Document Status
        $this->dokumenModel->update($id_dokumen, [
            'status' => 'Error',
            'processed_by' => session()->get('id_user'),
            'pernah_error' => 1
        ]);

        return redirect()->to('/dokumen')->with('error', 'Anomali berhasil dilaporkan.');
    }

    /**
     * Membersihkan dan memvalidasi input data untuk keamanan.
     * 
     * Langkah pembersihan:
     * 1. Trim: Menghapus spasi di awal dan akhir.
     * 2. Remove Invisible Characters: Menghapus karakter kontrol (null bytes, dll) yang tidak terlihat.
     * 3. Strip Tags: Menghapus tag HTML/PHP untuk mencegah XSS.
     * 4. HTML Special Chars: Mengubah karakter spesial menjadi entitas HTML untuk keamanan tampilan.
     * 
     * @param array $data Input data key-value
     * @return array Cleaned data
     */
    private function cleanData(array $data): array
    {
        $cleaned = [];
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // 1. Trim whitespace
                $value = trim($value);
                
                // 2. Remove invisible characters (e.g., null bytes) which can be used in exploits
                // Keeps standard whitespace (space, tab, newline, carriage return)
                $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
                
                // 3. Strip tags (XSS Prevention - remove <script>, <iframe>, etc.)
                $value = strip_tags($value);
                
                // 4. Convert special chars (XSS Prevention - encode <, >, ", ', &)
                // ENT_QUOTES ensures both single and double quotes are converted.
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
            $cleaned[$key] = $value;
        }
        return $cleaned;
    }
}
