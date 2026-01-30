<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Pcl extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $wilayah = $this->request->getVar('wilayah');
        $status = $this->request->getVar('status');

        $this->userModel->where('id_role', 3); // Role 3 = PCL

        if ($wilayah) {
            $this->userModel->like('wilayah_kerja', $wilayah);
        }
        if ($status !== null && $status !== '') {
            $this->userModel->where('is_active', $status);
        }

        $data = [
            'title' => 'Manajemen PCL',
            'pcls' => $this->userModel->paginate(10),
            'pager' => $this->userModel->pager,
            'filters' => [
                'wilayah' => $wilayah,
                'status' => $status
            ]
        ];

        return view('admin/pcl/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Registrasi PCL Baru',
            'action_url' => base_url('admin/pcl/store')
        ];
        return view('admin/pcl/form', $data);
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
            'wilayah_kerja' => 'required',
            'masa_tugas_start' => 'permit_empty|valid_date',
            'masa_tugas_end' => 'permit_empty|valid_date'
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
            'wilayah_kerja' => $this->request->getVar('wilayah_kerja'),
            'masa_tugas_start' => $this->request->getVar('masa_tugas_start'),
            'masa_tugas_end' => $this->request->getVar('masa_tugas_end'),
            'id_role' => 3, // Force Role PCL
            'is_active' => 1
        ];

        $this->userModel->save($data);
        return redirect()->to('admin/pcl')->with('success', 'PCL berhasil didaftarkan.');
    }

    public function edit($id)
    {
        $pcl = $this->userModel->find($id);
        if (!$pcl || $pcl['id_role'] != 3) {
            return redirect()->to('admin/pcl')->with('error', 'PCL tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Data PCL',
            'pcl' => $pcl,
            'action_url' => base_url('admin/pcl/update/' . $id)
        ];
        return view('admin/pcl/form', $data);
    }

    public function update($id)
    {
        $rules = [
            'fullname' => 'required|min_length[3]|max_length[100]',
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id_user,{$id}]",
            'email' => "required|valid_email|is_unique[users.email,id_user,{$id}]",
            'nik_ktp' => 'required|numeric|min_length[16]|max_length[16]',
            'phone_number' => 'required|numeric',
            'wilayah_kerja' => 'required'
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
            'wilayah_kerja' => $this->request->getVar('wilayah_kerja'),
            'masa_tugas_start' => $this->request->getVar('masa_tugas_start'),
            'masa_tugas_end' => $this->request->getVar('masa_tugas_end'),
            'is_active' => $this->request->getVar('is_active') ? 1 : 0
        ];

        // Optional Password Update
        if ($this->request->getVar('password')) {
            $data['password'] = $this->request->getVar('password');
        }

        $this->userModel->save($data);
        return redirect()->to('admin/pcl')->with('success', 'Data PCL berhasil diperbarui.');
    }

    public function export()
    {
        $pcls = $this->userModel->where('id_role', 3)->findAll();
        $filename = 'data_pcl_' . date('Ymd') . '.csv';

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        $file = fopen('php://output', 'w');
        $header = ['ID', 'Nama Lengkap', 'Username', 'Email', 'NIK', 'No Telp', 'Wilayah Kerja', 'Masa Tugas Mulai', 'Masa Tugas Selesai', 'Status'];
        fputcsv($file, $header);

        foreach ($pcls as $key => $value) {
            $line = [
                $value['id_user'],
                $value['fullname'],
                $value['username'],
                $value['email'],
                $value['nik_ktp'],
                $value['phone_number'],
                $value['wilayah_kerja'],
                $value['masa_tugas_start'],
                $value['masa_tugas_end'],
                $value['is_active'] ? 'Aktif' : 'Non-Aktif'
            ];
            fputcsv($file, $line);
        }
        fclose($file);
        exit;
    }

    public function importForm()
    {
        $data = [
            'title' => 'Import Data PCL'
        ];
        return view('admin/pcl/import', $data);
    }

    public function import()
    {
        $file = $this->request->getFile('csv_file');

        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid: ' . $file->getErrorString());
        }

        // Validate file extension
        $ext = $file->getClientExtension();
        if (!in_array($ext, ['csv', 'CSV'])) {
            return redirect()->back()->with('error', 'Format file harus CSV');
        }

        // Move file to temp location
        $newName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads', $newName);
        $filePath = WRITEPATH . 'uploads/' . $newName;

        // Use CsvImporter library
        $importer = new \App\Libraries\CsvImporter();
        $importer->setRequiredFields(['fullname', 'username', 'email', 'nik_ktp', 'phone_number', 'wilayah_kerja']);
        $importer->setFieldMapping([
            'Nama Lengkap' => 'fullname',
            'Username' => 'username',
            'Email' => 'email',
            'NIK' => 'nik_ktp',
            'No Telp' => 'phone_number',
            'Wilayah Kerja' => 'wilayah_kerja',
            'SOBAT ID' => 'sobat_id',
            'Masa Tugas Mulai' => 'masa_tugas_start',
            'Masa Tugas Selesai' => 'masa_tugas_end'
        ]);

        $data = $importer->parse($filePath);

        // Delete temp file
        @unlink($filePath);

        if ($importer->hasErrors()) {
            return redirect()->back()
                ->with('error', 'Terdapat error pada file CSV')
                ->with('import_errors', $importer->getErrors());
        }

        // Check for duplicate usernames/emails
        $duplicates = [];
        foreach ($data as $index => $row) {
            $existingUser = $this->userModel->where('username', $row['username'])->first();
            if ($existingUser) {
                $duplicates[] = "Baris " . ($index + 2) . ": Username '{$row['username']}' sudah terdaftar";
                continue;
            }
            $existingEmail = $this->userModel->where('email', $row['email'])->first();
            if ($existingEmail) {
                $duplicates[] = "Baris " . ($index + 2) . ": Email '{$row['email']}' sudah terdaftar";
                continue;
            }
        }

        if (!empty($duplicates)) {
            return redirect()->back()
                ->with('error', 'Terdapat data duplikat')
                ->with('import_errors', $duplicates);
        }

        // Insert valid data
        $successCount = 0;
        $defaultPassword = password_hash('pcl12345', PASSWORD_BCRYPT);
        
        foreach ($data as $row) {
            $insertData = [
                'fullname' => $row['fullname'],
                'username' => $row['username'],
                'email' => $row['email'],
                'password' => $defaultPassword,
                'nik_ktp' => $row['nik_ktp'],
                'phone_number' => $row['phone_number'],
                'sobat_id' => $row['sobat_id'] ?? null,
                'wilayah_kerja' => $row['wilayah_kerja'],
                'masa_tugas_start' => !empty($row['masa_tugas_start']) ? $row['masa_tugas_start'] : null,
                'masa_tugas_end' => !empty($row['masa_tugas_end']) ? $row['masa_tugas_end'] : null,
                'id_role' => 3, // PCL role
                'is_active' => 1
            ];
            
            // Insert directly to avoid double hashing
            $this->userModel->db->table('users')->insert($insertData);
            $successCount++;
        }

        // Audit log
        $audit = new \App\Models\AuditModel();
        $audit->log('PCL Import', "Imported $successCount PCL records", session()->get('id_user'));

        return redirect()->to('admin/pcl')->with('success', "$successCount data PCL berhasil diimport. Password default: pcl12345");
    }

    public function downloadTemplate()
    {
        $filename = 'template_import_pcl.csv';
        
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv");

        $file = fopen('php://output', 'w');
        
        // Header row
        $header = ['Nama Lengkap', 'Username', 'Email', 'NIK', 'No Telp', 'Wilayah Kerja', 'SOBAT ID', 'Masa Tugas Mulai', 'Masa Tugas Selesai'];
        fputcsv($file, $header);
        
        // Example data row
        $example = ['John Doe', 'johndoe', 'john@example.com', '3578012345678901', '081234567890', 'Kec. Sukolilo', 'SBID001', '2026-01-01', '2026-12-31'];
        fputcsv($file, $example);

        fclose($file);
        exit;
    }

    public function delete($id)
    {
        $pcl = $this->userModel->find($id);
        if (!$pcl || $pcl['id_role'] != 3) {
            return redirect()->to('admin/pcl')->with('error', 'PCL tidak ditemukan.');
        }

        // Soft delete - just mark as inactive
        $this->userModel->save(['id_user' => $id, 'is_active' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);

        // Audit log
        $audit = new \App\Models\AuditModel();
        $audit->log('PCL Deleted', "PCL $id ({$pcl['fullname']}) deleted", session()->get('id_user'));

        return redirect()->to('admin/pcl')->with('success', 'PCL berhasil dihapus.');
    }
}
