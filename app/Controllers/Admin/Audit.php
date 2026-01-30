<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AuditModel;
use App\Models\UserModel;

class Audit extends BaseController
{
    protected $auditModel;
    protected $userModel;

    public function __construct()
    {
        $this->auditModel = new AuditModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Get filter parameters
        $userId = $this->request->getVar('user_id');
        $action = $this->request->getVar('action');
        $dateFrom = $this->request->getVar('date_from');
        $dateTo = $this->request->getVar('date_to');
        $search = $this->request->getVar('search');

        // Build query with filters
        $builder = $this->auditModel;

        if ($userId) {
            $builder = $builder->where('user_id', $userId);
        }
        if ($action) {
            $builder = $builder->like('action', $action);
        }
        if ($dateFrom) {
            $builder = $builder->where('created_at >=', $dateFrom . ' 00:00:00');
        }
        if ($dateTo) {
            $builder = $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }
        if ($search) {
            $builder = $builder->groupStart()
                               ->like('action', $search)
                               ->orLike('details', $search)
                               ->orLike('ip_address', $search)
                               ->groupEnd();
        }

        $builder = $builder->orderBy('created_at', 'DESC');

        // Get users for filter dropdown
        $users = $this->userModel->select('id_user, fullname, username')->findAll();

        // Get unique actions for filter dropdown
        $db = \Config\Database::connect();
        $actions = $db->table('audit_logs')
                      ->select('DISTINCT(action) as action')
                      ->orderBy('action')
                      ->get()
                      ->getResultArray();

        // Summary statistics
        $todayCount = $this->auditModel->where('DATE(created_at)', date('Y-m-d'))->countAllResults();
        $weekCount = $this->auditModel->where('created_at >=', date('Y-m-d', strtotime('-7 days')))->countAllResults();
        $totalCount = $this->auditModel->countAllResults();

        $data = [
            'title' => 'Audit Trail',
            'logs' => $builder->paginate(25),
            'pager' => $this->auditModel->pager,
            'users' => $users,
            'actions' => array_column($actions, 'action'),
            'filters' => [
                'user_id' => $userId,
                'action' => $action,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'search' => $search
            ],
            'stats' => [
                'today' => $todayCount,
                'week' => $weekCount,
                'total' => $totalCount
            ]
        ];

        return view('admin/audit/index', $data);
    }

    public function export()
    {
        // Get filter parameters (same as index)
        $userId = $this->request->getVar('user_id');
        $action = $this->request->getVar('action');
        $dateFrom = $this->request->getVar('date_from');
        $dateTo = $this->request->getVar('date_to');

        $builder = $this->auditModel;

        if ($userId) {
            $builder = $builder->where('user_id', $userId);
        }
        if ($action) {
            $builder = $builder->like('action', $action);
        }
        if ($dateFrom) {
            $builder = $builder->where('created_at >=', $dateFrom . ' 00:00:00');
        }
        if ($dateTo) {
            $builder = $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }

        $logs = $builder->orderBy('created_at', 'DESC')->findAll();
        
        // Get user data for names
        $users = $this->userModel->findAll();
        $userMap = [];
        foreach ($users as $user) {
            $userMap[$user['id_user']] = $user['fullname'];
        }

        $filename = 'audit_log_' . date('Ymd_His') . '.csv';

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv");

        $file = fopen('php://output', 'w');
        
        // Header
        $header = ['No', 'Tanggal', 'User', 'Aksi', 'Detail', 'IP Address'];
        fputcsv($file, $header);

        foreach ($logs as $index => $log) {
            $userName = isset($userMap[$log['user_id']]) ? $userMap[$log['user_id']] : 'System';
            $line = [
                $index + 1,
                $log['created_at'],
                $userName,
                $log['action'],
                $log['details'],
                $log['ip_address']
            ];
            fputcsv($file, $line);
        }

        fclose($file);
        exit;
    }

    public function detail($id)
    {
        $log = $this->auditModel->find($id);
        
        if (!$log) {
            return redirect()->to('admin/audit')->with('error', 'Log tidak ditemukan.');
        }

        $user = null;
        if ($log['user_id']) {
            $user = $this->userModel->find($log['user_id']);
        }

        $data = [
            'title' => 'Detail Audit Log',
            'log' => $log,
            'user' => $user
        ];

        return view('admin/audit/detail', $data);
    }
}
