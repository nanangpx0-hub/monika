<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use Firebase\JWT\JWT;

class Auth extends ResourceController
{
    public function login()
    {
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        if (!$user) {
            return $this->failNotFound('Username not found');
        }

        if (password_verify($password, $user['password'])) {
            $key = getenv('JWT_SECRET') ?: 'monika_secret_key_2026';
            $payload = [
                'iss' => 'monika-api',
                'aud' => 'monika-mobile',
                'iat' => time(),
                'nbf' => time(),
                'exp' => time() + 3600, // 1 hour
                'uid' => $user['id_user'],
                'role' => $user['id_role']
            ];

            $token = JWT::encode($payload, $key, 'HS256');

            // Audit Log
            $audit = new \App\Models\AuditModel();
            $audit->log('API Login', "User {$user['username']} logged in via API.", $user['id_user']);

            return $this->respond([
                'status' => 200,
                'message' => 'Login Successful',
                'token' => $token,
                'user' => [
                    'id' => $user['id_user'],
                    'fullname' => $user['fullname'],
                    'role' => $user['id_role']
                ]
            ]);
        } else {
            return $this->failUnauthorized('Invalid Password');
        }
    }
}
