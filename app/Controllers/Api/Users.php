<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class Users extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format = 'json';

    public function index()
    {
        $search = $this->request->getVar('search');
        $role = $this->request->getVar('role');

        $model = new UserModel();

        if ($search) {
            $model->groupStart()
                ->like('fullname', $search)
                ->orLike('username', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }

        if ($role) {
            $model->where('id_role', $role);
        }

        $users = $model->paginate(10); // Default 10 per page

        return $this->respond([
            'status' => 200,
            'message' => 'Users retrieved successfully',
            'data' => $users,
            'pager' => $model->pager->getDetails()
        ]);
    }

    public function show($id = null)
    {
        $data = $this->model->find($id);
        if (!$data) {
            return $this->failNotFound('User not found');
        }
        // Remove password from response
        unset($data['password']);
        return $this->respond($data);
    }

    public function create()
    {
        $rules = [
            'fullname' => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confpassword' => 'required|matches[password]',
            'id_role' => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'fullname' => $this->request->getVar('fullname'),
            'username' => $this->request->getVar('username'),
            'email' => $this->request->getVar('email'),
            'password' => $this->request->getVar('password'),
            // Note: Password hashing is handled by Model callback 'hashPassword'
            'nik_ktp' => $this->request->getVar('nik_ktp'),
            'sobat_id' => $this->request->getVar('sobat_id'),
            'phone_number' => $this->request->getVar('phone_number'),
            'wilayah_kerja' => $this->request->getVar('wilayah_kerja'),
            'wilayah_supervisi' => $this->request->getVar('wilayah_supervisi'),
            'qualification' => $this->request->getVar('qualification'),
            'id_role' => $this->request->getVar('id_role'),
            'is_active' => 1
        ];

        if ($this->model->save($data)) {
            // Audit Log
            $audit = new \App\Models\AuditModel();
            $id = $this->model->getInsertID();
            // Get user ID from JWT if available (assumed from request or context) - creating for now as '0' or system if not extracted. 
            // Ideally we extract from token in filter and pass to request.
            // For now, let's assume API users are tracked by ID in logic if possible, or just log.
            $audit->log('API User Created', "User $id created via API.", 0);

            return $this->respondCreated(['status' => 201, 'message' => 'User created successfully']);
        }

        return $this->failServerError('Failed to create user');
    }

    public function update($id = null)
    {
        $user = $this->model->find($id);
        if (!$user) {
            return $this->failNotFound('User not found');
        }

        $rules = [
            'fullname' => 'if_exist|min_length[3]|max_length[100]',
            'username' => "if_exist|min_length[3]|max_length[50]|is_unique[users.username,id_user,{$id}]",
            'email' => "if_exist|valid_email|is_unique[users.email,id_user,{$id}]",
            'id_role' => 'if_exist|integer'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Get Raw JSON data
        $data = $this->request->getJSON(true);
        if (empty($data)) {
            // Fallback to form data for compatibility
            $data = $this->request->getRawInput();
        }

        // Handle password update separately if provided
        if (isset($data['password']) && !empty($data['password'])) {
            if (strlen($data['password']) < 6) {
                return $this->failValidationErrors(['password' => 'Password must be at least 6 characters']);
            }
            // Model callback will hash it
        } else {
            unset($data['password']); // Prevent overwriting with empty or null
        }

        if ($this->model->update($id, $data)) {
            // Audit Log
            $audit = new \App\Models\AuditModel();
            $audit->log('API User Updated', "User $id updated via API.", 0);

            return $this->respond(['status' => 200, 'message' => 'User updated successfully']);
        }

        return $this->failServerError('Failed to update user');
    }

    public function delete($id = null)
    {
        $user = $this->model->find($id);
        if (!$user) {
            return $this->failNotFound('User not found');
        }

        if ($this->model->delete($id)) {
            // Audit Log
            $audit = new \App\Models\AuditModel();
            $audit->log('API User Deleted', "User $id deleted via API.", 0); // User tracking needs improvement in API

            return $this->respondDeleted(['status' => 200, 'message' => 'User deleted successfully']);
        }

        return $this->failServerError('Failed to delete user');
    }
}
