<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Login | MONIKA'
        ];
        return view('auth/login', $data);
    }

    public function loginProcess()
    {
        $session = session();
        $model = new UserModel();
        $loginId = $this->request->getVar('login_id'); // Can be email or username
        $password = $this->request->getVar('password');

        $user = $model->where('username', $loginId)
                      ->orWhere('email', $loginId)
                      ->first();

        if ($user) {
            if ($user['is_active'] == 0) {
                $session->setFlashdata('error', 'Akun Anda non-aktif. Hubungi Administrator.');
                return redirect()->to('/login');
            }

            if (password_verify($password, $user['password'])) {
                // Get Active Activity
                $kegiatanModel = new \App\Models\KegiatanModel();
                $activeKegiatan = $kegiatanModel->where('status', 'Aktif')->first();
                
                $sessData = [
                    'id_user'   => $user['id_user'],
                    'username'  => $user['username'],
                    'fullname'  => $user['fullname'],
                    'id_role'   => $user['id_role'],
                    'active_kegiatan_id' => $activeKegiatan ? $activeKegiatan['id_kegiatan'] : null,
                    'active_kegiatan_name' => $activeKegiatan ? $activeKegiatan['nama_kegiatan'] : 'Tidak ada kegiatan aktif',
                    'is_logged_in' => true
                ];
                $session->set($sessData);
                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('error', 'Password salah.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Username/Email tidak ditemukan.');
            return redirect()->to('/login');
        }
    }

    public function register()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Registrasi Mitra | MONIKA'
        ];
        return view('auth/register', $data);
    }

    public function registerProcess()
    {
        $rules = [
            'fullname' => 'required|min_length(3)|max_length(100)',
            'username' => 'required|min_length(3)|max_length(50)|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length(6)',
            'confpassword' => 'matches[password]',
            'nik_ktp'  => 'required|min_length(16)|max_length(16)|numeric',
            'sobat_id' => 'required',
            'id_role'  => 'required|in_list[3,4]' // 3=PCL, 4=Pengolahan
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $data = [
            'fullname' => $this->request->getVar('fullname'),
            'username' => $this->request->getVar('username'),
            'email'    => $this->request->getVar('email'),
            'password' => $this->request->getVar('password'),
            'nik_ktp'  => $this->request->getVar('nik_ktp'),
            'sobat_id' => $this->request->getVar('sobat_id'),
            'id_role'  => $this->request->getVar('id_role'),
            'is_active'=> 1 // Auto active as per requirement implicit
        ];

        $userModel->save($data);

        session()->setFlashdata('success', 'Registrasi berhasil. Silakan login.');
        return redirect()->to('/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
