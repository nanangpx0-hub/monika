<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AuditModel;

class Audit extends BaseController
{
    public function index()
    {
        $model = new AuditModel();

        $data = [
            'title' => 'Laporan Aktivitas User',
            'logs' => $model->orderBy('created_at', 'DESC')->paginate(20),
            'pager' => $model->pager
        ];

        return view('admin/audit/index', $data);
    }
}
