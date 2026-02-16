<?php

namespace App\Controllers;

use App\Models\LaporanModel;
use App\Models\KegiatanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Dompdf\Dompdf;
use Dompdf\Options;

class Laporan extends BaseController
{
    protected LaporanModel $laporanModel;
    protected KegiatanModel $kegiatanModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
        $this->kegiatanModel = new KegiatanModel();
    }

    /**
     * Extract common filter params from GET request.
     */
    private function getFilters(): array
    {
        return [
            'kegiatan'  => $this->request->getGet('kegiatan') ? (int) $this->request->getGet('kegiatan') : null,
            'date_from' => $this->request->getGet('date_from') ?: null,
            'date_to'   => $this->request->getGet('date_to') ?: null,
        ];
    }

    /**
     * Dashboard Laporan — Charts, Stats, DataTable
     */
    public function index()
    {
        if (session()->get('id_role') != 1) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $filters = $this->getFilters();

        // Use CI4 page cache for heavy stats (5 minutes)
        $cacheKey = 'laporan_stats_' . md5(json_encode($filters));
        $cachedData = cache($cacheKey);

        if ($cachedData) {
            $stats = $cachedData['stats'];
            $statusSummary = $cachedData['statusSummary'];
            $targetRealisasi = $cachedData['targetRealisasi'];
        } else {
            $stats = $this->laporanModel->getSummaryStats(
                $filters['kegiatan'], $filters['date_from'], $filters['date_to']
            );
            $statusSummary = $this->laporanModel->getStatusSummary(
                $filters['kegiatan'], $filters['date_from'], $filters['date_to']
            );
            $targetRealisasi = $this->laporanModel->getTargetVsRealisasi(
                $filters['kegiatan'], $filters['date_from'], $filters['date_to']
            );

            // Cache for 5 minutes
            cache()->save($cacheKey, [
                'stats' => $stats,
                'statusSummary' => $statusSummary,
                'targetRealisasi' => $targetRealisasi,
            ], 300);
        }

        $dokumenList = $this->laporanModel->getDokumenList(
            $filters['kegiatan'], $filters['date_from'], $filters['date_to']
        );

        $data = [
            'title'            => 'Dashboard Laporan',
            'stats'            => $stats,
            'statusSummary'    => $statusSummary,
            'targetRealisasi'  => $targetRealisasi,
            'dokumenList'      => $dokumenList,
            'listKegiatan'     => $this->kegiatanModel->findAll(),
            'filters'          => $filters,
        ];

        return view('laporan/index', $data);
    }

    /**
     * Export data to Excel using PhpSpreadsheet.
     */
    public function exportExcel()
    {
        if (session()->get('id_role') != 1) {
            return redirect()->to('/dashboard');
        }

        $filters = $this->getFilters();
        $dokumen = $this->laporanModel->getDokumenList(
            $filters['kegiatan'], $filters['date_from'], $filters['date_to']
        );

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Dokumen');

        // Header styling
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0099D8']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];

        $cellStyle = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ];

        // Title row
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'LAPORAN DOKUMEN SURVEI — MONIKA');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Filter info
        $filterInfo = 'Tanggal: ' . ($filters['date_from'] ?: 'Semua') . ' s/d ' . ($filters['date_to'] ?: 'Semua');
        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', $filterInfo);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Headers
        $headers = ['No', 'Kode Wilayah', 'Nama PCL', 'Sobat ID', 'Kegiatan', 'Status', 'Tanggal Setor', 'Pernah Error'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '4', $header);
            $col++;
        }
        $sheet->getStyle('A4:H4')->applyFromArray($headerStyle);

        // Data rows
        $row = 5;
        foreach ($dokumen as $i => $doc) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $doc['kode_wilayah']);
            $sheet->setCellValue('C' . $row, $doc['nama_pcl'] ?? '-');
            $sheet->setCellValue('D' . $row, $doc['sobat_id'] ?? '-');
            $sheet->setCellValue('E' . $row, $doc['nama_kegiatan'] ?? '-');
            $sheet->setCellValue('F' . $row, $doc['status']);
            $sheet->setCellValue('G' . $row, $doc['tanggal_setor'] ?? '-');
            $sheet->setCellValue('H' . $row, $doc['pernah_error'] ? 'Ya' : 'Tidak');
            $row++;
        }

        // Apply cell styles
        $lastRow = $row - 1;
        if ($lastRow >= 5) {
            $sheet->getStyle("A5:H{$lastRow}")->applyFromArray($cellStyle);
        }

        // Auto-size columns
        foreach (range('A', 'H') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        // Output
        $filename = 'Laporan_Dokumen_' . date('Y-m-d_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Export data to PDF using Dompdf.
     */
    public function exportPdf()
    {
        if (session()->get('id_role') != 1) {
            return redirect()->to('/dashboard');
        }

        $filters = $this->getFilters();
        $dokumen = $this->laporanModel->getDokumenList(
            $filters['kegiatan'], $filters['date_from'], $filters['date_to']
        );
        $stats = $this->laporanModel->getSummaryStats(
            $filters['kegiatan'], $filters['date_from'], $filters['date_to']
        );

        $html = view('laporan/pdf_template', [
            'dokumen' => $dokumen,
            'stats'   => $stats,
            'filters' => $filters,
            'tanggal' => date('d F Y'),
        ]);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'sans-serif');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'Laporan_Dokumen_' . date('Y-m-d_His') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }

    // ─── Keep existing methods ───────────────────────────────────────

    /**
     * Laporan Kinerja PCL (existing).
     */
    public function pcl()
    {
        if (session()->get('id_role') != 1) {
            return redirect()->to('/dashboard');
        }

        $id_kegiatan = $this->request->getGet('kegiatan');
        $dokumenModel = new \App\Models\DokumenModel();

        $data = [
            'title' => 'Laporan Kinerja PCL',
            'list_kegiatan' => $this->kegiatanModel->findAll(),
            'selected_kegiatan' => $id_kegiatan,
            'laporan' => $dokumenModel->getPclPerformance($id_kegiatan)
        ];

        return view('laporan/pcl', $data);
    }

    /**
     * Laporan Kinerja Pengolahan (existing).
     */
    public function pengolahan()
    {
        if (session()->get('id_role') != 1) {
            return redirect()->to('/dashboard');
        }

        $id_kegiatan = $this->request->getGet('kegiatan');
        $dokumenModel = new \App\Models\DokumenModel();

        $data = [
            'title' => 'Laporan Kinerja Pengolahan',
            'list_kegiatan' => $this->kegiatanModel->findAll(),
            'selected_kegiatan' => $id_kegiatan,
            'laporan' => $dokumenModel->getProcessorPerformance($id_kegiatan)
        ];

        return view('laporan/pengolahan', $data);
    }
}
