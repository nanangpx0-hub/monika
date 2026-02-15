<?php

namespace App\Controllers;

use App\Models\LogistikModel;

class Logistik extends BaseController
{
    protected LogistikModel $logistikModel;

    public function __construct()
    {
        $this->logistikModel = new LogistikModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $tableReady = $db->tableExists('logistik');

        $data = [
            'title' => 'Logistik',
            'table_ready' => $tableReady,
            'barang_list' => [],
        ];

        if ($tableReady) {
            $data['barang_list'] = $this->logistikModel
                ->orderBy('nama_barang', 'ASC')
                ->findAll();
        }

        return view('logistik/index', $data);
    }
}
