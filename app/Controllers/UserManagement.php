<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserAuditModel;

class UserManagement extends BaseController
{
    protected UserModel $userModel;
    protected UserAuditModel $auditModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->auditModel = new UserAuditModel();
    }

    /**
     * Guard — redirect non-admins.
     */
    private function adminOnly()
    {
        if (session()->get('id_role') != 1) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }
        return null;
    }

    // ── LIST ──────────────────────────────────────────────────────

    public function index()
    {
        if ($guard = $this->adminOnly()) return $guard;

        $filterRole = $this->request->getGet('role') ? (int) $this->request->getGet('role') : null;

        $data = [
            'title'      => 'Kelola Pengguna',
            'users'      => $this->userModel->getWithRole($filterRole),
            'roles'      => $this->userModel->getRoles(),
            'supervisors'=> $this->userModel->getSupervisors(),
            'filterRole' => $filterRole,
        ];

        return view('users/index', $data);
    }

    // ── CREATE ────────────────────────────────────────────────────

    public function store()
    {
        if ($guard = $this->adminOnly()) return $guard;

        $rules = [
            'fullname' => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'id_role'  => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $insertData = [
            'fullname'      => trim($this->request->getPost('fullname')),
            'username'      => trim($this->request->getPost('username')),
            'email'         => trim($this->request->getPost('email')),
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT, ['cost' => 12]),
            'id_role'       => (int) $this->request->getPost('id_role'),
            'nik_ktp'       => trim($this->request->getPost('nik_ktp') ?? ''),
            'sobat_id'      => trim($this->request->getPost('sobat_id') ?? ''),
            'id_supervisor' => $this->request->getPost('id_supervisor') ? (int) $this->request->getPost('id_supervisor') : null,
            'is_active'     => 1,
        ];

        if (! $this->userModel->save($insertData)) {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        $newUserId = $this->userModel->getInsertID();
        $this->auditModel->logAction($newUserId, 'created', (int) session()->get('id_user'), $insertData);

        return redirect()->to('/users')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // ── UPDATE ────────────────────────────────────────────────────

    public function update($id)
    {
        if ($guard = $this->adminOnly()) return $guard;

        $existing = $this->userModel->find($id);
        if (! $existing) {
            return redirect()->to('/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        $rules = [
            'fullname' => 'required|min_length[3]|max_length[100]',
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id_user,{$id}]",
            'email'    => "required|valid_email|is_unique[users.email,id_user,{$id}]",
            'id_role'  => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'fullname'      => trim($this->request->getPost('fullname')),
            'username'      => trim($this->request->getPost('username')),
            'email'         => trim($this->request->getPost('email')),
            'id_role'       => (int) $this->request->getPost('id_role'),
            'nik_ktp'       => trim($this->request->getPost('nik_ktp') ?? ''),
            'sobat_id'      => trim($this->request->getPost('sobat_id') ?? ''),
            'id_supervisor' => $this->request->getPost('id_supervisor') ? (int) $this->request->getPost('id_supervisor') : null,
        ];

        $this->userModel->update($id, $updateData);

        $this->auditModel->logAction((int) $id, 'updated', (int) session()->get('id_user'), [
            'before' => array_intersect_key($existing, $updateData),
            'after'  => $updateData,
        ]);

        return redirect()->to('/users')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    // ── RESET PASSWORD ────────────────────────────────────────────

    public function resetPassword($id)
    {
        if ($guard = $this->adminOnly()) return $guard;

        $existing = $this->userModel->find($id);
        if (! $existing) {
            return redirect()->to('/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        $newPassword = $this->request->getPost('new_password');
        if (empty($newPassword) || strlen($newPassword) < 6) {
            return redirect()->back()->with('error', 'Password baru minimal 6 karakter.');
        }

        $this->userModel->update($id, [
            'password' => password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]),
        ]);

        $this->auditModel->logAction((int) $id, 'password_reset', (int) session()->get('id_user'), [
            'username' => $existing['username'],
        ]);

        return redirect()->to('/users')->with('success', 'Password pengguna "' . esc($existing['username']) . '" berhasil direset.');
    }

    // ── TOGGLE ACTIVE ─────────────────────────────────────────────

    public function toggleActive($id)
    {
        if ($guard = $this->adminOnly()) return $guard;

        $existing = $this->userModel->find($id);
        if (! $existing) {
            return redirect()->to('/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        // Cannot deactivate yourself
        if ((int) $id === (int) session()->get('id_user')) {
            return redirect()->to('/users')->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $newStatus = ((int) $existing['is_active'] === 1) ? 0 : 1;
        $this->userModel->update($id, ['is_active' => $newStatus]);

        $action = $newStatus ? 'activated' : 'deactivated';
        $this->auditModel->logAction((int) $id, $action, (int) session()->get('id_user'), [
            'username' => $existing['username'],
        ]);

        $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->to('/users')->with('success', 'Pengguna "' . esc($existing['username']) . '" berhasil ' . $statusText . '.');
    }

    // ── DELETE ─────────────────────────────────────────────────────

    public function delete($id)
    {
        if ($guard = $this->adminOnly()) return $guard;

        $existing = $this->userModel->find($id);
        if (! $existing) {
            return redirect()->to('/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        // Cannot delete yourself
        if ((int) $id === (int) session()->get('id_user')) {
            return redirect()->to('/users')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $this->userModel->delete($id);

        $this->auditModel->logAction((int) $id, 'deleted', (int) session()->get('id_user'), [
            'username' => $existing['username'],
            'email'    => $existing['email'],
        ]);

        return redirect()->to('/users')->with('success', 'Pengguna "' . esc($existing['username']) . '" berhasil dihapus.');
    }
}
