<?php

namespace App\Controllers;

use App\Models\DokumenModel;
use App\Models\KegiatanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
            'role_id' => $role_id,
            'kegiatan' => $this->kegiatanModel->where('status', 'Aktif')->findAll(),
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
            'id_petugas_pendataan' => session()->get('id_user'),
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

    // ── EXCEL IMPORT ──────────────────────────────────────────────

    /**
     * Download blank Excel template for bulk document submission.
     */
    public function downloadTemplate()
    {
        if (!in_array(session()->get('id_role'), [1, 3])) {
            return redirect()->to('/dokumen')->with('error', 'Akses ditolak.');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Setor Dokumen');

        // Headers
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Wilayah');

        // Style headers
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '0099D8']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:B1')->applyFromArray($headerStyle);

        // Example rows
        $sheet->setCellValue('A2', 1);
        $sheet->setCellValueExplicit('B2', '3509120001', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('A3', 2);
        $sheet->setCellValueExplicit('B3', '3509120002', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('A4', 3);
        $sheet->setCellValueExplicit('B4', '3509120003', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(25);

        // Add instruction note
        $sheet->setCellValue('D1', 'PETUNJUK:');
        $sheet->setCellValue('D2', '1. Isi kolom "Kode Wilayah" dengan kode NBS/ID SLS.');
        $sheet->setCellValue('D3', '2. Kolom "No" bersifat opsional (untuk urutan saja).');
        $sheet->setCellValue('D4', '3. Hapus baris contoh sebelum mengimpor.');
        $sheet->setCellValue('D5', '4. Simpan file dalam format .xlsx');
        $sheet->getStyle('D1')->getFont()->setBold(true);
        $sheet->getColumnDimension('D')->setWidth(50);

        $writer = new Xlsx($spreadsheet);

        $filename = 'Template_Setor_Dokumen_' . date('Ymd') . '.xlsx';

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody((function () use ($writer) {
                ob_start();
                $writer->save('php://output');
                return ob_get_clean();
            })());
    }

    /**
     * Parse uploaded Excel and return preview data as JSON.
     */
    public function importPreview()
    {
        if (!in_array(session()->get('id_role'), [1, 3])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Akses ditolak.']);
        }

        $file = $this->request->getFile('excel_file');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'File tidak valid atau tidak ditemukan.']);
        }

        // Validate extension
        $ext = strtolower($file->getClientExtension());
        if ($ext !== 'xlsx') {
            return $this->response->setJSON(['success' => false, 'message' => 'Format file harus .xlsx']);
        }

        // Validate size (max 2MB)
        if ($file->getSize() > 2 * 1024 * 1024) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ukuran file maksimal 2MB.']);
        }

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal membaca file Excel: ' . $e->getMessage()]);
        }

        if (count($rows) < 2) {
            return $this->response->setJSON(['success' => false, 'message' => 'File Excel kosong atau hanya berisi header.']);
        }

        // Parse rows (skip header row 1)
        $preview = [];
        $errorCount = 0;

        foreach ($rows as $rowNum => $row) {
            if ($rowNum === 1) continue; // skip header

            $kodeWilayah = trim((string) ($row['B'] ?? ''));

            // Skip completely empty rows
            if ($kodeWilayah === '') continue;

            $rowError = '';

            // Validate kode_wilayah
            if (strlen($kodeWilayah) < 3) {
                $rowError = 'Kode wilayah terlalu pendek (minimal 3 karakter)';
                $errorCount++;
            } elseif (strlen($kodeWilayah) > 20) {
                $rowError = 'Kode wilayah terlalu panjang (maksimal 20 karakter)';
                $errorCount++;
            }

            $preview[] = [
                'row'           => $rowNum,
                'kode_wilayah'  => $kodeWilayah,
                'error'         => $rowError,
            ];
        }

        if (empty($preview)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada data kode wilayah yang ditemukan di kolom B.']);
        }

        return $this->response->setJSON([
            'success'    => true,
            'data'       => $preview,
            'total'      => count($preview),
            'errors'     => $errorCount,
            'valid'      => count($preview) - $errorCount,
        ]);
    }

    /**
     * Bulk-insert confirmed import rows.
     */
    public function importStore()
    {
        if (!in_array(session()->get('id_role'), [1, 3])) {
            return redirect()->to('/dokumen')->with('error', 'Akses ditolak.');
        }

        $idKegiatan = $this->request->getPost('import_id_kegiatan');
        $tanggalSetor = $this->request->getPost('import_tanggal_setor');
        $kodeWilayahList = $this->request->getPost('kode_wilayah_list');

        if (empty($idKegiatan) || empty($tanggalSetor) || empty($kodeWilayahList)) {
            return redirect()->to('/dokumen')->with('error', 'Data impor tidak lengkap.');
        }

        // Decode the JSON list
        $kodeList = json_decode($kodeWilayahList, true);
        if (!is_array($kodeList) || empty($kodeList)) {
            return redirect()->to('/dokumen')->with('error', 'Tidak ada data untuk diimpor.');
        }

        $inserted = 0;
        $failed = 0;
        $userId = session()->get('id_user');

        foreach ($kodeList as $kode) {
            $kode = trim((string) $kode);
            if ($kode === '' || strlen($kode) < 3 || strlen($kode) > 20) {
                $failed++;
                continue;
            }

            try {
                $this->dokumenModel->save([
                    'id_kegiatan'          => (int) $idKegiatan,
                    'kode_wilayah'         => $kode,
                    'tanggal_setor'        => $tanggalSetor,
                    'id_petugas_pendataan' => $userId,
                    'status'               => 'Uploaded',
                ]);
                $inserted++;
            } catch (\Throwable $e) {
                $failed++;
            }
        }

        $msg = "Impor selesai: {$inserted} dokumen berhasil disetor.";
        if ($failed > 0) {
            $msg .= " ({$failed} gagal)";
        }

        return redirect()->to('/dokumen')->with('success', $msg);
    }
}
