<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PmlActivityModel;

class Pml extends BaseController
{
    protected $userModel;
    protected $activityModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->activityModel = new PmlActivityModel();
    }

    public function index()
    {
        $this->userModel->where('id_role', 5); // Role 5 = PML
        $data = [
            'title' => 'Manajemen PML',
            'pmls' => $this->userModel->paginate(10),
            'pager' => $this->userModel->pager
        ];
        return view('admin/pml/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Registrasi PML Baru',
            'action_url' => base_url('admin/pml/store')
        ];
        return view('admin/pml/form', $data);
    }

    public function store()
    {
        $rules = [
            'fullname' => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'nik_ktp' => 'required|numeric|min_length[16]|max_length[16]',
            'phone_number' => 'required|numeric',
            'wilayah_supervisi' => 'required',
            'qualification' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'fullname' => $this->request->getVar('fullname'),
            'username' => $this->request->getVar('username'),
            'email' => $this->request->getVar('email'),
            'password' => $this->request->getVar('password'),
            'nik_ktp' => $this->request->getVar('nik_ktp'),
            'phone_number' => $this->request->getVar('phone_number'),
            'wilayah_supervisi' => $this->request->getVar('wilayah_supervisi'),
            'qualification' => $this->request->getVar('qualification'),
            'id_role' => 5, // Force Role PML
            'is_active' => 1
        ];

        $this->userModel->save($data);
        return redirect()->to('admin/pml')->with('success', 'PML berhasil didaftarkan.');
    }

    public function edit($id)
    {
        $pml = $this->userModel->find($id);
        if (!$pml || $pml['id_role'] != 5) {
            return redirect()->to('admin/pml')->with('error', 'PML tidak ditemukan.');
        }

        // Get PCLs assigned to this PML
        $assignedPcls = $this->userModel->where('id_role', 3)->where('id_supervisor', $id)->findAll();
        
        // Get PCLs without supervisor (available)
        $availablePcls = $this->userModel->where('id_role', 3)->where('id_supervisor', null)->findAll();

        $data = [
            'title' => 'Edit Data PML',
            'pml' => $pml,
            'assignedPcls' => $assignedPcls,
            'availablePcls' => $availablePcls,
            'action_url' => base_url('admin/pml/update/' . $id)
        ];
        return view('admin/pml/form', $data);
    }

    public function update($id)
    {
        $rules = [
            'fullname' => 'required|min_length[3]|max_length[100]',
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id_user,{$id}]",
            'email' => "required|valid_email|is_unique[users.email,id_user,{$id}]",
            'nik_ktp' => 'required|numeric|min_length[16]|max_length[16]',
            'phone_number' => 'required|numeric',
            'wilayah_supervisi' => 'required',
            'qualification' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_user' => $id,
            'fullname' => $this->request->getVar('fullname'),
            'username' => $this->request->getVar('username'),
            'email' => $this->request->getVar('email'),
            'nik_ktp' => $this->request->getVar('nik_ktp'),
            'phone_number' => $this->request->getVar('phone_number'),
            'wilayah_supervisi' => $this->request->getVar('wilayah_supervisi'),
            'qualification' => $this->request->getVar('qualification'),
            'is_active' => $this->request->getVar('is_active') ? 1 : 0
        ];

        if ($this->request->getVar('password')) {
            $data['password'] = $this->request->getVar('password');
        }

        $this->userModel->save($data);
        return redirect()->to('admin/pml')->with('success', 'Data PML berhasil diperbarui.');
    }

    public function assignPcl()
    {
        $pmlId = $this->request->getVar('pml_id');
        $pclIds = $this->request->getVar('pcl_ids'); // Array of IDs

        // 1. Reset all PCLs currently assigned to this PML (optional, if we want to replace)
        // Or better: just update the selected ones to this PML.
        // If we want to support unassigning, we need to handle that.
        // Let's assume the form sends the *complete* list of PCLs for this PML.
        // But UI might be "Add PCL to this PML".
        
        // Implementation: Add selected PCLs to this PML
        if ($pclIds && is_array($pclIds)) {
            foreach ($pclIds as $pclId) {
                $this->userModel->save(['id_user' => $pclId, 'id_supervisor' => $pmlId]);
            }
        }

        return redirect()->back()->with('success', 'PCL berhasil ditugaskan.');
    }
    
    public function unassignPcl($pclId)
    {
        $this->userModel->save(['id_user' => $pclId, 'id_supervisor' => null]);
        return redirect()->back()->with('success', 'PCL berhasil dilepas dari tugas.');
    }

    public function monitoring($id)
    {
        $pml = $this->userModel->find($id);
        $activities = $this->activityModel->where('id_pml', $id)->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'title' => 'Monitoring Aktivitas PML',
            'pml' => $pml,
            'activities' => $activities
        ];
        return view('admin/pml/monitoring', $data);
    }

    public function performanceReport($id)
    {
        $pml = $this->userModel->find($id);
        if (!$pml || $pml['id_role'] != 5) {
            return redirect()->to('admin/pml')->with('error', 'PML tidak ditemukan.');
        }

        // Get PCLs under this PML
        $assignedPcls = $this->userModel->where('id_role', 3)->where('id_supervisor', $id)->findAll();

        // Get activity statistics
        $summary = $this->activityModel->getActivitySummary($id);
        $monthlyStats = $this->activityModel->getMonthlyStats($id);
        $recentActivities = $this->activityModel->getRecentActivities($id, 20);

        // Calculate PCL-related statistics
        $pclStats = [];
        $db = \Config\Database::connect();
        foreach ($assignedPcls as $pcl) {
            // Count documents with errors from this PCL
            $errorCount = $db->table('dokumen_survei')
                             ->where('id_petugas_pendataan', $pcl['id_user'])
                             ->where('status', 'Error')
                             ->countAllResults();
            $totalDocs = $db->table('dokumen_survei')
                            ->where('id_petugas_pendataan', $pcl['id_user'])
                            ->countAllResults();
            
            $pclStats[] = [
                'pcl' => $pcl,
                'error_count' => $errorCount,
                'total_docs' => $totalDocs,
                'error_rate' => $totalDocs > 0 ? round(($errorCount / $totalDocs) * 100, 1) : 0
            ];
        }

        $data = [
            'title' => 'Laporan Kinerja PML: ' . $pml['fullname'],
            'pml' => $pml,
            'summary' => $summary,
            'monthlyStats' => $monthlyStats,
            'recentActivities' => $recentActivities,
            'assignedPcls' => $assignedPcls,
            'pclStats' => $pclStats,
            'totalPcl' => count($assignedPcls)
        ];

        return view('admin/pml/performance', $data);
    }

    public function exportPerformance($id)
    {
        $pml = $this->userModel->find($id);
        if (!$pml) {
            return redirect()->to('admin/pml')->with('error', 'PML tidak ditemukan.');
        }

        $activities = $this->activityModel->where('id_pml', $id)->orderBy('created_at', 'DESC')->findAll();
        $filename = 'laporan_kinerja_' . str_replace(' ', '_', $pml['fullname']) . '_' . date('Ymd') . '.csv';

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv");

        $file = fopen('php://output', 'w');
        
        // Report Header Info
        fputcsv($file, ['Laporan Kinerja PML']);
        fputcsv($file, ['Nama', $pml['fullname']]);
        fputcsv($file, ['Tanggal Export', date('d-m-Y H:i:s')]);
        fputcsv($file, []);
        
        // Activities Header
        $header = ['No', 'Tanggal', 'Jenis Aktivitas', 'Deskripsi', 'Latitude', 'Longitude'];
        fputcsv($file, $header);

        foreach ($activities as $index => $activity) {
            $line = [
                $index + 1,
                $activity['created_at'],
                $activity['activity_type'],
                $activity['description'],
                $activity['location_lat'] ?? '-',
                $activity['location_long'] ?? '-'
            ];
            fputcsv($file, $line);
        }

        fclose($file);
        exit;
    }

    public function logActivity()
    {
        $pmlId = $this->request->getVar('pml_id');
        $type = $this->request->getVar('activity_type');
        $description = $this->request->getVar('description');
        $lat = $this->request->getVar('location_lat');
        $long = $this->request->getVar('location_long');

        if (!$pmlId || !$type) {
            return redirect()->back()->with('error', 'Data aktivitas tidak lengkap.');
        }

        $this->activityModel->logActivity($pmlId, $type, $description, $lat, $long);

        return redirect()->back()->with('success', 'Aktivitas berhasil dicatat.');
    }

    public function delete($id)
    {
        $pml = $this->userModel->find($id);
        if (!$pml || $pml['id_role'] != 5) {
            return redirect()->to('admin/pml')->with('error', 'PML tidak ditemukan.');
        }

        // Unassign all PCLs first
        $this->userModel->where('id_supervisor', $id)->set(['id_supervisor' => null])->update();
        
        // Soft delete
        $this->userModel->save(['id_user' => $id, 'is_active' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);

        // Audit log
        $audit = new \App\Models\AuditModel();
        $audit->log('PML Deleted', "PML $id ({$pml['fullname']}) deleted", session()->get('id_user'));

        return redirect()->to('admin/pml')->with('success', 'PML berhasil dihapus.');
    }
}
