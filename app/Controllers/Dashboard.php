<?php

namespace App\Controllers;

use App\Models\KartuKendaliModel;
use App\Models\NksModel;
use App\Models\TandaTerimaModel;
use DateTime;

class Dashboard extends BaseController
{
    public function index()
    {
        $nksModel         = new NksModel();
        $tandaTerimaModel = new TandaTerimaModel();
        $kartuKendaliModel = new KartuKendaliModel();

        $totalNks = $nksModel->countAllResults();

        $sumDokumen = $tandaTerimaModel->selectSum('jml_ruta_terima')->first();
        $dokMasuk   = (int) ($sumDokumen['jml_ruta_terima'] ?? 0);

        $totalEntry = $kartuKendaliModel->countAllResults();
        $persenEntry = $dokMasuk > 0 ? round(($totalEntry / $dokMasuk) * 100, 2) : 0;

        $today    = new DateTime(date('Y-m-d'));
        $deadline = new DateTime(date('Y-m-t'));
        $sisaHari = (int) $today->diff($deadline)->days;

        $data = [
            'total_nks'   => $totalNks,
            'dok_masuk'   => $dokMasuk,
            'persen_entry' => $persenEntry,
            'sisa_hari'   => $sisaHari,
        ];

        return view('dashboard/index', $data);
    }
}
