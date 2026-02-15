<?php

namespace App\Controllers;

use App\Models\KartuKendaliModel;
use App\Models\NksModel;
use App\Models\TandaTerimaModel;

class KartuKendali extends BaseController
{
    protected $kartuKendaliModel;
    protected $nksModel;
    protected $tandaTerimaModel;

    public function __construct()
    {
        $this->kartuKendaliModel = new KartuKendaliModel();
        $this->nksModel = new NksModel();
        $this->tandaTerimaModel = new TandaTerimaModel();
    }

    /**
     * Display list of NKS with progress
     */
    public function index()
    {
        $data = [
            'title' => 'Kartu Kendali Digital',
            'nks_list' => $this->kartuKendaliModel->getProgressByNks()
        ];

        return view('kartu_kendali/index', $data);
    }

    /**
     * Display detail grid for specific NKS (10 ruta boxes)
     */
    public function detail($nks)
    {
        // Get NKS info
        $nksInfo = $this->nksModel->find($nks);
        if (!$nksInfo) {
            return redirect()->to('/kartu-kendali')->with('error', 'NKS tidak ditemukan.');
        }

        // Get tanda terima info (how many ruta received)
        $tandaTerima = $this->tandaTerimaModel->where('nks', $nks)->first();
        $jmlRutaTerima = $tandaTerima ? (int)$tandaTerima['jml_ruta_terima'] : 0;

        // Get all entries for this NKS
        $entries = $this->kartuKendaliModel->getEntriesByNks($nks);
        
        // Map entries by no_ruta for easy lookup
        $entriesMap = [];
        foreach ($entries as $entry) {
            $entriesMap[$entry['no_ruta']] = $entry;
        }

        // Get current user ID
        $currentUserId = session()->get('id_user') ?? session()->get('id');

        // Prepare 10 ruta boxes with status logic
        $rutaBoxes = [];
        for ($i = 1; $i <= 10; $i++) {
            $box = [
                'no_ruta' => $i,
                'status' => 'OPEN', // Default
                'status_entry' => null,
                'is_patch_issue' => 0,
                'user_name' => null,
                'user_id' => null,
                'tgl_entry' => null,
                'can_edit' => false,
            ];

            // Logic 1: LOCKED_LOGISTIC - Document not received yet
            if ($i > $jmlRutaTerima) {
                $box['status'] = 'LOCKED_LOGISTIC';
            }
            // Logic 2 & 3: Check if already worked on
            elseif (isset($entriesMap[$i])) {
                $entry = $entriesMap[$i];
                $box['status_entry'] = $entry['status_entry'];
                $box['is_patch_issue'] = $entry['is_patch_issue'];
                $box['user_id'] = $entry['user_id'];
                $box['user_name'] = $entry['fullname'] ?? $entry['nama'] ?? 'Unknown';
                $box['tgl_entry'] = $entry['tgl_entry'];

                // If owned by current user
                if ($entry['user_id'] == $currentUserId) {
                    $box['status'] = 'DONE';
                    $box['can_edit'] = true;
                } else {
                    // Owned by another user
                    $box['status'] = 'LOCKED_USER';
                }
            }
            // Logic 4: OPEN - Ready to work
            else {
                $box['status'] = 'OPEN';
            }

            $rutaBoxes[] = $box;
        }

        $data = [
            'title' => 'Detail Kartu Kendali - NKS ' . $nks,
            'nks' => $nks,
            'nks_info' => $nksInfo,
            'jml_ruta_terima' => $jmlRutaTerima,
            'ruta_boxes' => $rutaBoxes,
        ];

        return view('kartu_kendali/detail', $data);
    }

    /**
     * Store new entry or update existing
     */
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('error', 'Invalid request.');
        }

        $nks = $this->request->getPost('nks');
        $noRuta = (int)$this->request->getPost('no_ruta');
        $statusEntry = $this->request->getPost('status_entry');
        $isPatchIssue = $this->request->getPost('is_patch_issue') ? 1 : 0;
        $currentUserId = session()->get('id_user') ?? session()->get('id');

        // Validation
        if (!$nks || !$noRuta || !$statusEntry) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data tidak lengkap.'
            ]);
        }

        // Check if ruta already taken by another user
        if ($this->kartuKendaliModel->isRutaTaken($nks, $noRuta, $currentUserId)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Ruta ini sudah dikerjakan oleh petugas lain.'
            ]);
        }

        // Check if current user already has entry for this ruta (for update)
        $existing = $this->kartuKendaliModel
            ->where('nks', $nks)
            ->where('no_ruta', $noRuta)
            ->where('user_id', $currentUserId)
            ->first();

        $data = [
            'nks' => $nks,
            'no_ruta' => $noRuta,
            'user_id' => $currentUserId,
            'status_entry' => $statusEntry,
            'is_patch_issue' => $isPatchIssue,
            'tgl_entry' => date('Y-m-d'),
        ];

        if ($existing) {
            // Update existing entry
            $result = $this->kartuKendaliModel->update($existing['id'], $data);
            $message = 'Data berhasil diperbarui.';
        } else {
            // Insert new entry
            $result = $this->kartuKendaliModel->insert($data);
            $message = 'Data berhasil disimpan.';
        }

        if ($result) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => $message
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal menyimpan data.',
                'errors' => $this->kartuKendaliModel->errors()
            ]);
        }
    }

    /**
     * Delete entry (only own entry)
     */
    public function delete()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('error', 'Invalid request.');
        }

        $nks = $this->request->getPost('nks');
        $noRuta = (int)$this->request->getPost('no_ruta');
        $currentUserId = session()->get('id_user') ?? session()->get('id');

        // Find entry
        $entry = $this->kartuKendaliModel
            ->where('nks', $nks)
            ->where('no_ruta', $noRuta)
            ->where('user_id', $currentUserId)
            ->first();

        if (!$entry) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data tidak ditemukan atau bukan milik Anda.'
            ]);
        }

        if ($this->kartuKendaliModel->delete($entry['id'])) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data berhasil dihapus.'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal menghapus data.'
            ]);
        }
    }
}

