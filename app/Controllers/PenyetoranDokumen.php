<?php

namespace App\Controllers;

use App\Models\PenyetoranDokumenModel;
use App\Models\KegiatanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PenyetoranDokumen extends BaseController
{
    protected $model;
    protected $kegiatanModel;

    public function __construct()
    {
        $this->model = new PenyetoranDokumenModel();
        $this->kegiatanModel = new KegiatanModel();
    }

    /**
     * Helper: only roles 1 (Admin), 3 (PCL/Sosial) can create.
     */
    private function canCreate(): bool
    {
        return in_array((int) session()->get('id_role'), [1, 3]);
    }

    /**
     * Helper: only roles 1 (Admin), 4 (Pengolahan/PLS) can confirm.
     */
    private function canConfirm(): bool
    {
        return in_array((int) session()->get('id_role'), [1, 4]);
    }

    // ── LIST ──────────────────────────────────────────────────────

    public function index()
    {
        $filterStatus = $this->request->getGet('status') ?: null;

        $data = [
            'title'        => 'Penyetoran Dokumen',
            'submissions'  => $this->model->getAll($filterStatus),
            'filterStatus' => $filterStatus,
            'canCreate'    => $this->canCreate(),
            'canConfirm'   => $this->canConfirm(),
        ];

        return view('penyetoran/index', $data);
    }

    // ── CREATE ────────────────────────────────────────────────────

    public function create()
    {
        if (!$this->canCreate()) {
            return redirect()->to('/penyetoran')->with('error', 'Akses ditolak.');
        }

        $data = [
            'title'    => 'Setor Dokumen Baru',
            'kegiatan' => $this->kegiatanModel->where('status', 'Aktif')->findAll(),
        ];

        return view('penyetoran/create', $data);
    }

    public function store()
    {
        if (!$this->canCreate()) {
            return redirect()->to('/penyetoran')->with('error', 'Akses ditolak.');
        }

        // Validate header
        $rules = [
            'id_kegiatan'         => 'required|integer',
            'nama_pengirim'       => 'required|min_length[3]|max_length[100]',
            'tanggal_penyerahan'  => 'required|valid_date',
            'jenis_dokumen'       => 'required|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle file upload
        $filePath = null;
        $file = $this->request->getFile('file_pendukung');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/penyetoran', $newName);
            $filePath = 'uploads/penyetoran/' . $newName;
        }

        // Save header
        $headerData = [
            'id_kegiatan'        => $this->request->getPost('id_kegiatan'),
            'nama_pengirim'      => $this->request->getPost('nama_pengirim'),
            'tanggal_penyerahan' => $this->request->getPost('tanggal_penyerahan'),
            'jenis_dokumen'      => $this->request->getPost('jenis_dokumen'),
            'keterangan'         => $this->request->getPost('keterangan'),
            'file_pendukung'     => $filePath,
            'id_penyerah'        => session()->get('id_user'),
            'status'             => 'Diserahkan',
        ];

        $this->model->save($headerData);
        $idPenyetoran = $this->model->getInsertID();

        // Parse detail rows
        $kodeProp = $this->request->getPost('kode_prop');
        $kodeKab  = $this->request->getPost('kode_kab');
        $kodeNks  = $this->request->getPost('kode_nks');
        $noRuta   = $this->request->getPost('no_urut_ruta');
        $statusArr = $this->request->getPost('status_selesai');
        $tglArr   = $this->request->getPost('tgl_penerimaan');

        $rows = [];
        if (is_array($kodeNks)) {
            for ($i = 0; $i < count($kodeNks); $i++) {
                if (empty(trim($kodeNks[$i] ?? ''))) continue;
                $rows[] = [
                    'kode_prop'      => str_pad(trim($kodeProp[$i] ?? ''), 2, '0', STR_PAD_LEFT),
                    'kode_kab'       => str_pad(trim($kodeKab[$i] ?? ''), 2, '0', STR_PAD_LEFT),
                    'kode_nks'       => str_pad(trim($kodeNks[$i]), 5, '0', STR_PAD_LEFT),
                    'no_urut_ruta'   => (int) ($noRuta[$i] ?? 0),
                    'status_selesai' => ($statusArr[$i] ?? 'belum') === 'sudah' ? 'sudah' : 'belum',
                    'tgl_penerimaan' => !empty($tglArr[$i]) ? $tglArr[$i] : null,
                ];
            }
        }

        $inserted = 0;
        if (!empty($rows)) {
            $inserted = $this->model->insertBatchDetail($idPenyetoran, $rows);
        }

        return redirect()->to('/penyetoran')->with('success', "Penyetoran berhasil disimpan ({$inserted} dokumen).");
    }

    // ── DETAIL ────────────────────────────────────────────────────

    public function detail($id)
    {
        $submission = $this->model->getWithDetails((int) $id);
        if (!$submission) {
            return redirect()->to('/penyetoran')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title'      => 'Detail Penyetoran #' . $id,
            'submission' => $submission,
            'canConfirm' => $this->canConfirm(),
        ];

        return view('penyetoran/detail', $data);
    }

    // ── CONFIRM ───────────────────────────────────────────────────

    public function confirm($id)
    {
        if (!$this->canConfirm()) {
            return redirect()->to('/penyetoran')->with('error', 'Akses ditolak.');
        }

        $status  = $this->request->getPost('status');
        $catatan = $this->request->getPost('catatan_penerima');

        if (!in_array($status, ['Diterima', 'Ditolak'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $this->model->confirmReceipt((int) $id, (int) session()->get('id_user'), $status, $catatan);

        $label = $status === 'Diterima' ? 'diterima' : 'ditolak';
        return redirect()->to('/penyetoran/detail/' . $id)->with('success', "Penyetoran telah {$label}.");
    }

    // ── EXCEL TEMPLATE ────────────────────────────────────────────

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Penyetoran');

        // Headers matching Excel format
        $headers = ['A' => 'kode prop (2 digit)', 'B' => 'kode kab (2 digit)', 'C' => 'kode NKS (5 digit)', 'D' => 'No Urut Ruta (max 2 digit)', 'E' => 'Sudah Selesai? (sudah / belum)', 'F' => 'TGL_PENERIMAAN'];
        foreach ($headers as $col => $label) {
            $sheet->setCellValue($col . '1', $label);
        }

        // Style
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E65100']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(40);

        // Format row
        $sheet->setCellValue('F2', 'TT-BB-TTTT');
        $sheet->getStyle('F2')->getFont()->setItalic(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('999999'));

        // Example data
        $examples = [
            ['35', '09', '00051', 1, 'belum', ''],
            ['35', '09', '00051', 2, 'sudah', '05-02-2026'],
            ['35', '09', '00051', 3, 'sudah', '05-02-2026'],
        ];
        foreach ($examples as $i => $row) {
            $r = $i + 3;
            $sheet->setCellValueExplicit('A' . $r, $row[0], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('B' . $r, $row[1], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C' . $r, $row[2], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('D' . $r, $row[3]);
            $sheet->setCellValue('E' . $r, $row[4]);
            $sheet->setCellValue('F' . $r, $row[5]);
        }

        // Column widths
        $widths = ['A' => 14, 'B' => 14, 'C' => 16, 'D' => 20, 'E' => 22, 'F' => 18];
        foreach ($widths as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Penyetoran_Dokumen_' . date('Ymd') . '.xlsx';

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

    // ── IMPORT PREVIEW ────────────────────────────────────────────

    public function importPreview()
    {
        if (!$this->canCreate()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Akses ditolak.']);
        }

        $file = $this->request->getFile('excel_file');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'File tidak valid.']);
        }

        if (strtolower($file->getClientExtension()) !== 'xlsx') {
            return $this->response->setJSON(['success' => false, 'message' => 'Format file harus .xlsx']);
        }

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal membaca: ' . $e->getMessage()]);
        }

        if (count($rows) < 2) {
            return $this->response->setJSON(['success' => false, 'message' => 'File kosong atau hanya header.']);
        }

        $preview = [];
        $errors = 0;

        foreach ($rows as $num => $row) {
            if ($num <= 2) continue; // skip header + format row

            $kodeProp = str_pad(trim((string)($row['A'] ?? '')), 2, '0', STR_PAD_LEFT);
            $kodeKab  = str_pad(trim((string)($row['B'] ?? '')), 2, '0', STR_PAD_LEFT);
            $kodeNks  = trim((string)($row['C'] ?? ''));
            $noRuta   = (int)($row['D'] ?? 0);
            $status   = strtolower(trim((string)($row['E'] ?? 'belum')));
            $tgl      = trim((string)($row['F'] ?? ''));

            if ($kodeNks === '' && $noRuta === 0) continue;

            $error = '';
            if (strlen($kodeNks) < 3) { $error = 'Kode NKS terlalu pendek'; $errors++; }
            elseif ($noRuta < 1 || $noRuta > 99) { $error = 'No Urut Ruta harus 1–99'; $errors++; }

            // Parse date DD-MM-YYYY to Y-m-d
            $parsedDate = null;
            if ($tgl !== '' && $status === 'sudah') {
                $dParts = explode('-', $tgl);
                if (count($dParts) === 3) {
                    $parsedDate = "{$dParts[2]}-{$dParts[1]}-{$dParts[0]}";
                }
            }

            $preview[] = [
                'row'          => $num,
                'kode_prop'    => $kodeProp,
                'kode_kab'     => $kodeKab,
                'kode_nks'     => $kodeNks,
                'no_urut_ruta' => $noRuta,
                'status'       => $status === 'sudah' ? 'sudah' : 'belum',
                'tgl'          => $parsedDate,
                'error'        => $error,
            ];
        }

        if (empty($preview)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada data ditemukan.']);
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $preview,
            'total'   => count($preview),
            'errors'  => $errors,
            'valid'   => count($preview) - $errors,
        ]);
    }
}
