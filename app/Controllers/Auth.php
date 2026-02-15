<?php

namespace App\Controllers;

use Config\Database;
use Exception;

class Auth extends BaseController
{
    private const REMEMBER_COOKIE = 'monika_remember';
    private const REMEMBER_DAYS = 30;

    private array $userColumns = [];

    public function index()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        if ($this->attemptRememberLogin()) {
            return redirect()->to(base_url('dashboard'));
        }

        $data = [
            'title' => 'MONIKA LOGIN',
        ];

        return view('auth/login', $data);
    }

    public function login()
    {
        $identity = trim((string) ($this->request->getPost('username') ?? $this->request->getPost('email') ?? ''));
        $password = (string) $this->request->getPost('password');
        $remember = (string) $this->request->getPost('remember_me');

        $validationData = [
            'username' => $identity,
            'password' => $password,
        ];

        $validationRules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validateData($validationData, $validationRules)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Username/Email dan password wajib diisi.',
                    'errors' => $this->validator->getErrors(),
                ]);
        }

        $user = $this->findUserByIdentity($identity);
        if (! $user || ! isset($user['password']) || ! password_verify($password, (string) $user['password'])) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Password/Username salah',
                ]);
        }

        if ($this->hasUserColumn('is_active') && isset($user['is_active']) && (int) $user['is_active'] === 0) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Akun tidak aktif.',
                ]);
        }

        $this->setSessionUser($user);

        if (in_array(strtolower($remember), ['1', 'on', 'true', 'yes'], true)) {
            $this->setRememberCookie((int) $this->extractUserId($user));
        } else {
            $this->clearRememberCookie();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'redirect' => base_url('dashboard'),
        ]);
    }

    public function logout()
    {
        $session = session();
        $session->remove([
            'id',
            'id_user',
            'role',
            'id_role',
            'nama',
            'fullname',
            'username',
            'is_logged_in',
        ]);
        $session->regenerate(true);
        $session->destroy();

        $response = redirect()->to(base_url('login?logout=1'));
        $response->deleteCookie(self::REMEMBER_COOKIE, '', '/');
        $response->deleteCookie(config('Session')->cookieName, '', '/');

        return $response;
    }

    private function attemptRememberLogin(): bool
    {
        $token = (string) ($this->request->getCookie(self::REMEMBER_COOKIE) ?? '');
        if ($token === '') {
            return false;
        }

        $payload = $this->decryptRememberToken($token);
        if ($payload === null || ! isset($payload['id'], $payload['exp'])) {
            $this->clearRememberCookie();
            return false;
        }

        if ((int) $payload['exp'] < time()) {
            $this->clearRememberCookie();
            return false;
        }

        $user = $this->findUserById((int) $payload['id']);
        if (! $user) {
            $this->clearRememberCookie();
            return false;
        }

        if ($this->hasUserColumn('is_active') && isset($user['is_active']) && (int) $user['is_active'] === 0) {
            $this->clearRememberCookie();
            return false;
        }

        $this->setSessionUser($user);
        return true;
    }

    private function setSessionUser(array $user): void
    {
        $userId = $this->extractUserId($user);
        $roleValue = $this->extractRole($user);
        $namaValue = $this->extractName($user);

        session()->regenerate(true);
        session()->set([
            'id' => $userId,
            'id_user' => $userId,
            'role' => $roleValue,
            'id_role' => $roleValue,
            'nama' => $namaValue,
            'fullname' => $namaValue,
            'username' => $user['username'] ?? null,
            'is_logged_in' => true,
        ]);
    }

    private function findUserByIdentity(string $identity): ?array
    {
        $builder = Database::connect()->table('users');
        $builder->where('username', $identity);

        if ($this->hasUserColumn('email')) {
            $builder->orWhere('email', $identity);
        }

        return $builder->get()->getRowArray();
    }

    private function findUserById(int $id): ?array
    {
        $idColumn = $this->hasUserColumn('id') ? 'id' : 'id_user';
        $builder = Database::connect()->table('users');
        return $builder->where($idColumn, $id)->get()->getRowArray();
    }

    private function extractUserId(array $user): int
    {
        if (isset($user['id'])) {
            return (int) $user['id'];
        }

        return (int) ($user['id_user'] ?? 0);
    }

    private function extractRole(array $user): string
    {
        if (isset($user['role'])) {
            return (string) $user['role'];
        }

        return (string) ($user['id_role'] ?? '');
    }

    private function extractName(array $user): string
    {
        if (isset($user['nama'])) {
            return (string) $user['nama'];
        }

        if (isset($user['fullname'])) {
            return (string) $user['fullname'];
        }

        return (string) ($user['username'] ?? 'User');
    }

    private function setRememberCookie(int $userId): void
    {
        $payload = [
            'id' => $userId,
            'exp' => time() + (self::REMEMBER_DAYS * 86400),
        ];

        $token = $this->encryptRememberToken($payload);

        $this->response->setCookie(
            self::REMEMBER_COOKIE,
            $token,
            self::REMEMBER_DAYS * 86400,
            '',
            '/',
            '',
            false,
            true,
            'Lax'
        );
    }

    private function clearRememberCookie(): void
    {
        $this->response->deleteCookie(self::REMEMBER_COOKIE, '', '/');
    }

    private function encryptRememberToken(array $payload): string
    {
        $plaintext = json_encode($payload);
        if ($plaintext === false) {
            throw new Exception('Gagal memproses token remember me.');
        }

        $iv = random_bytes(16);
        $ciphertext = openssl_encrypt($plaintext, 'AES-256-CBC', $this->rememberKey(), OPENSSL_RAW_DATA, $iv);
        if ($ciphertext === false) {
            throw new Exception('Gagal mengenkripsi token remember me.');
        }

        return $this->base64UrlEncode($iv . $ciphertext);
    }

    private function decryptRememberToken(string $token): ?array
    {
        $decoded = $this->base64UrlDecode($token);
        if ($decoded === false || strlen($decoded) <= 16) {
            return null;
        }

        $iv = substr($decoded, 0, 16);
        $ciphertext = substr($decoded, 16);

        $plaintext = openssl_decrypt($ciphertext, 'AES-256-CBC', $this->rememberKey(), OPENSSL_RAW_DATA, $iv);
        if ($plaintext === false) {
            return null;
        }

        $payload = json_decode($plaintext, true);
        if (! is_array($payload)) {
            return null;
        }

        return $payload;
    }

    private function rememberKey(): string
    {
        $base = (string) env('auth.remember.key', '');
        if ($base === '') {
            $base = (string) env('app.baseURL', config('App')->baseURL);
        }

        return hash('sha256', $base . '|monika|remember', true);
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string|false
    {
        $padding = strlen($data) % 4;
        if ($padding !== 0) {
            $data .= str_repeat('=', 4 - $padding);
        }

        return base64_decode(strtr($data, '-_', '+/'), true);
    }

    private function hasUserColumn(string $column): bool
    {
        if ($this->userColumns === []) {
            $this->userColumns = Database::connect()->getFieldNames('users');
        }

        return in_array($column, $this->userColumns, true);
    }
}
