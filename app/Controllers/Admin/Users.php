<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RolesModel; // Assuming we might need this, or just hardcode role mapping

class Users extends BaseController
{
    public function index()
    {
        $model = new UserModel();

        // Simple search pagination
        $search = $this->request->getVar('search');
        if ($search) {
            $model->like('fullname', $search)
                ->orLike('username', $search)
                ->orLike('email', $search);
        }

        $data = [
            'title' => 'Manajemen User',
            'users' => $model->paginate(10),
            'pager' => $model->pager,
            'search' => $search
        ];

        return view('admin/users/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah User Baru',
            'action_url' => base_url('admin/users/store')
        ];
        return view('admin/users/form', $data);
    }

    public function store()
    {
        $rules = [
            'fullname' => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confpassword' => 'required|matches[password]',
            'id_role' => 'required|integer',
            'nik_ktp' => 'permit_empty|numeric|min_length[16]|max_length[16]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $data = [
            'fullname' => $this->request->getVar('fullname'),
            'username' => $this->request->getVar('username'),
            'email' => $this->request->getVar('email'),
            'password' => $this->request->getVar('password'),
            // Model handles hashing
            'id_role' => $this->request->getVar('id_role'),
            'nik_ktp' => $this->request->getVar('nik_ktp'),
            'sobat_id' => $this->request->getVar('sobat_id'),
            'phone_number' => $this->request->getVar('phone_number'),
            'is_active' => 1
        ];

        if ($userModel->save($data)) {
            // Audit Log
            $audit = new \App\Models\AuditModel();
            $id = $userModel->getInsertID();
            $audit->log('User Created', "User $id ({$data['username']}) created by Admin.", session()->get('id_user'));

            return redirect()->to('admin/users')->with('success', 'User berhasil ditambahkan.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan user.');
    }

    public function edit($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'action_url' => base_url('admin/users/update/' . $id),
            'method' => 'PUT'
        ];

        return view('admin/users/form', $data);
    }

    public function update($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User tidak ditemukan.');
        }

        $rules = [
            'fullname' => 'required|min_length[3]|max_length[100]',
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id_user,{$id}]",
            'email' => "required|valid_email|is_unique[users.email,id_user,{$id}]",
            'id_role' => 'required|integer'
        ];

        // Password validation only if filled
        $password = $this->request->getVar('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
            $rules['confpassword'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_user' => $id,
            'fullname' => $this->request->getVar('fullname'),
            'username' => $this->request->getVar('username'),
            'email' => $this->request->getVar('email'),
            'id_role' => $this->request->getVar('id_role'),
            'nik_ktp' => $this->request->getVar('nik_ktp'),
            'sobat_id' => $this->request->getVar('sobat_id'),
            'phone_number' => $this->request->getVar('phone_number'),
            'is_active' => $this->request->getVar('is_active') ? 1 : 0
        ];

        if (!empty($password)) {
            $data['password'] = $password;
        }

        if ($userModel->save($data)) {
            // Audit Log
            $audit = new \App\Models\AuditModel();
            $audit->log('User Updated', "User $id ({$data['username']}) updated by Admin.", session()->get('id_user'));

            return redirect()->to('admin/users')->with('success', 'User berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui user.');
    }

    public function delete($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id); // Get user data before delete for log

        // Soft delete instead of hard delete
        if ($userModel->save(['id_user' => $id, 'is_active' => 0, 'deleted_at' => date('Y-m-d H:i:s')])) {
            // Audit Log
            $audit = new \App\Models\AuditModel();
            $audit->log('User Deleted', "User $id ({$user['username']}) deleted by Admin.", session()->get('id_user'));

            return redirect()->to('admin/users')->with('success', 'User berhasil dihapus.');
        }
        return redirect()->to('admin/users')->with('error', 'Gagal menghapus user.');
    }

    public function resetPasswordForm($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User tidak ditemukan.');
        }

        $data = [
            'title' => 'Reset Password',
            'user' => $user
        ];

        return view('admin/users/reset_password', $data);
    }

    public function resetPassword($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User tidak ditemukan.');
        }

        $newPassword = $this->request->getVar('new_password');
        $generateRandom = $this->request->getVar('generate_random');

        if ($generateRandom) {
            $newPassword = $this->generatePassword();
        }

        if (empty($newPassword) || strlen($newPassword) < 6) {
            return redirect()->back()->with('error', 'Password minimal 6 karakter.');
        }

        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $db = \Config\Database::connect();
        $db->table('users')->where('id_user', $id)->update([
            'password' => $hashedPassword,
            'password_changed_at' => date('Y-m-d H:i:s')
        ]);

        // Audit Log
        $audit = new \App\Models\AuditModel();
        $audit->log('Password Reset', "Password for user $id ({$user['username']}) reset by Admin.", session()->get('id_user'));

        if ($generateRandom) {
            return redirect()->to('admin/users')->with('success', "Password berhasil direset. Password baru: $newPassword");
        }

        return redirect()->to('admin/users')->with('success', 'Password berhasil direset.');
    }

    public function toggleStatus($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User tidak ditemukan.');
        }

        $newStatus = $user['is_active'] ? 0 : 1;
        $statusLabel = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

        $userModel->save(['id_user' => $id, 'is_active' => $newStatus]);

        // Audit Log
        $audit = new \App\Models\AuditModel();
        $audit->log('User Status Changed', "User $id ({$user['username']}) $statusLabel.", session()->get('id_user'));

        return redirect()->to('admin/users')->with('success', "User berhasil $statusLabel.");
    }

    /**
     * Generate a random secure password
     */
    protected function generatePassword(int $length = 10): string
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%&*';
        
        $password = '';
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];
        
        $allChars = $uppercase . $lowercase . $numbers . $special;
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }
        
        return str_shuffle($password);
    }
}

