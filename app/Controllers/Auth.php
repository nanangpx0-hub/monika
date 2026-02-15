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

    public function registerForm()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('auth/register', [
            'title' => 'MONIKA REGISTER',
        ]);
    }

    public function register()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        $validationRules = [
            'username'     => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'password'     => 'required|min_length[6]',
            'confpassword' => 'required|matches[password]',
        ];

        if ($this->hasUserColumn('email')) {
            $validationRules['email'] = 'required|valid_email|is_unique[users.email]';
        }

        if ($this->hasUserColumn('fullname') || $this->hasUserColumn('nama')) {
            $validationRules['fullname'] = 'required|min_length[3]|max_length[100]';
        }

        if ($this->hasUserColumn('id_role')) {
            $validationRules['id_role'] = 'required|integer';
        }

        if ($this->hasUserColumn('nik_ktp')) {
            $validationRules['nik_ktp'] = 'permit_empty|numeric|exact_length[16]';
        }

        if ($this->hasUserColumn('sobat_id')) {
            $validationRules['sobat_id'] = 'permit_empty|max_length[50]';
        }

        if (! $this->validate($validationRules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = trim((string) $this->request->getPost('username'));
        $fullname = trim((string) $this->request->getPost('fullname'));
        $email = trim((string) $this->request->getPost('email'));
        $roleId = trim((string) $this->request->getPost('id_role'));
        $nikKtp = trim((string) $this->request->getPost('nik_ktp'));
        $sobatId = trim((string) $this->request->getPost('sobat_id'));

        if ($fullname === '') {
            $fullname = $username;
        }

        $insertData = [];

        if ($this->hasUserColumn('username')) {
            $insertData['username'] = $username;
        }

        if ($this->hasUserColumn('password')) {
            $insertData['password'] = password_hash((string) $this->request->getPost('password'), PASSWORD_BCRYPT);
        }

        if ($this->hasUserColumn('fullname')) {
            $insertData['fullname'] = $fullname;
        }

        if ($this->hasUserColumn('nama')) {
            $insertData['nama'] = $fullname;
        }

        if ($this->hasUserColumn('email')) {
            $insertData['email'] = $email;
        }

        if ($this->hasUserColumn('id_role')) {
            $insertData['id_role'] = $roleId === '' ? 3 : (int) $roleId;
        }

        if ($this->hasUserColumn('role')) {
            $insertData['role'] = $this->mapLegacyRole($roleId);
        }

        if ($this->hasUserColumn('nik_ktp') && $nikKtp !== '') {
            $insertData['nik_ktp'] = $nikKtp;
        }

        if ($this->hasUserColumn('sobat_id') && $sobatId !== '') {
            $insertData['sobat_id'] = $sobatId;
        }

        if ($this->hasUserColumn('is_active')) {
            $insertData['is_active'] = 1;
        }

        $now = date('Y-m-d H:i:s');
        if ($this->hasUserColumn('created_at')) {
            $insertData['created_at'] = $now;
        }
        if ($this->hasUserColumn('updated_at')) {
            $insertData['updated_at'] = $now;
        }

        if ($insertData === []) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', ['register' => 'Struktur tabel users tidak mendukung proses registrasi.']);
        }

        $builder = Database::connect()->table('users');
        $result = $builder->insert($insertData);

        if (! $result) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', ['register' => 'Gagal menyimpan akun baru.']);
        }

        return redirect()
            ->to(base_url('login'))
            ->with('success', 'Registrasi berhasil. Silakan login menggunakan akun baru Anda.');
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
            ENVIRONMENT === 'production',
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
            throw new Exception('auth.remember.key harus dikonfigurasi di .env untuk fitur Remember Me.');
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

    private function mapLegacyRole(string $idRole): string
    {
        return match ($idRole) {
            '1' => 'admin',
            '4' => 'petugas_pengolahan',
            '5' => 'pimpinan',
            default => 'operator_sosial',
        };
    }
}
