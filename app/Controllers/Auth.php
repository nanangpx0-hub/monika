<?php

namespace App\Controllers;

use App\Models\LoginAttemptModel;
use Config\Database;
use Exception;

class Auth extends BaseController
{
    private const REMEMBER_COOKIE = 'monika_remember';
    private const REMEMBER_DAYS = 30;
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_TIME = 15; // minutes

    private array $userColumns = [];
    private LoginAttemptModel $loginAttemptModel;

    public function __construct()
    {
        $this->loginAttemptModel = new LoginAttemptModel();
    }

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
        $ipAddress = $this->request->getIPAddress();
        $identity = trim((string) ($this->request->getPost('username') ?? $this->request->getPost('email') ?? ''));
        $password = (string) $this->request->getPost('password');
        $remember = (string) $this->request->getPost('remember_me');

        // Check rate limiting
        $failedAttemptsByIp = $this->loginAttemptModel->getFailedAttemptsCount($ipAddress, self::LOCKOUT_TIME);
        $failedAttemptsByUsername = $identity !== ''
            ? $this->loginAttemptModel->getFailedAttemptsCountByUsername($identity, self::LOCKOUT_TIME)
            : 0;
        $failedAttempts = max($failedAttemptsByIp, $failedAttemptsByUsername);

        if ($failedAttempts >= self::MAX_LOGIN_ATTEMPTS) {
            $this->logError('Rate limit exceeded', [
                'ip' => $ipAddress,
                'username' => $identity,
                'attempts_ip' => $failedAttemptsByIp,
                'attempts_username' => $failedAttemptsByUsername,
            ]);

            return $this->response
                ->setStatusCode(429)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . self::LOCKOUT_TIME . ' menit.',
                ]);
        }

        // Validate input
        $validationData = [
            'username' => $identity,
            'password' => $password,
        ];

        $validationRules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validateData($validationData, $validationRules)) {
            $errorMsg = 'Username/Email dan password wajib diisi.';
            $this->loginAttemptModel->logAttempt($ipAddress, $identity, false, $errorMsg);

            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => 'error',
                    'message' => $errorMsg,
                    'errors' => $this->validator->getErrors(),
                ]);
        }

        // Check database connection
        try {
            $db = Database::connect();
            if (! $db->connID) {
                throw new Exception('Database connection failed');
            }
        } catch (Exception $e) {
            $this->logError('Database connection error', [
                'error' => $e->getMessage(),
                'ip' => $ipAddress,
            ]);

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan sistem. Silakan hubungi administrator.',
                ]);
        }

        // Find user
        $user = $this->findUserByIdentity($identity);
        if (! $user || ! isset($user['password']) || ! password_verify($password, (string) $user['password'])) {
            $errorMsg = 'Username atau password salah.';
            $this->loginAttemptModel->logAttempt($ipAddress, $identity, false, $errorMsg);

            $this->logError('Invalid credentials', [
                'ip' => $ipAddress,
                'username' => $identity,
                'user_found' => $user !== null,
            ]);

            return $this->response
                ->setStatusCode(401)
                ->setJSON([
                    'status' => 'error',
                    'message' => $errorMsg,
                ]);
        }

        // Check if account is active
        if ($this->hasUserColumn('is_active') && isset($user['is_active']) && (int) $user['is_active'] === 0) {
            $errorMsg = 'Akun Anda tidak aktif. Silakan hubungi administrator.';
            $this->loginAttemptModel->logAttempt($ipAddress, $identity, false, $errorMsg);

            $this->logError('Inactive account', [
                'ip' => $ipAddress,
                'username' => $identity,
                'user_id' => $this->extractUserId($user),
            ]);

            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status' => 'error',
                    'message' => $errorMsg,
                ]);
        }

        // Check session directory
        if (! $this->ensureSessionDirectory()) {
            $this->logError('Session directory not writable', [
                'path' => WRITEPATH . 'session',
            ]);

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan sistem. Silakan hubungi administrator.',
                ]);
        }

        // Set session
        try {
            $this->setSessionUser($user);

            // Log successful login
            $this->loginAttemptModel->logAttempt($ipAddress, $identity, true);

            // Opportunistic housekeeping to keep login_attempts table compact.
            if (random_int(1, 20) === 1) {
                $this->loginAttemptModel->clearOldAttempts();
            }

            $this->logInfo('Login successful', [
                'ip' => $ipAddress,
                'username' => $identity,
                'user_id' => $this->extractUserId($user),
            ]);
        } catch (Exception $e) {
            $this->logError('Session creation failed', [
                'error' => $e->getMessage(),
                'ip' => $ipAddress,
                'username' => $identity,
            ]);

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal membuat sesi login. Silakan coba lagi.',
                ]);
        }

        // Handle remember me
        if (in_array(strtolower($remember), ['1', 'on', 'true', 'yes'], true)) {
            try {
                $this->setRememberCookie((int) $this->extractUserId($user));
            } catch (Exception $e) {
                // Remember me failed, but login still successful
                $this->logError('Remember me cookie failed', [
                    'error' => $e->getMessage(),
                    'user_id' => $this->extractUserId($user),
                ]);
            }
        } else {
            $this->clearRememberCookie();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Login berhasil.',
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

        // Set session data first, then regenerate
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

        // Regenerate session ID for security
        session()->regenerate(false);

        // Verify session was set correctly
        if (! session()->get('is_logged_in')) {
            throw new Exception('Session data not persisted');
        }
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
            $db = Database::connect();
            $columns = $db->getFieldNames('users');

            if ($columns === [] || $columns === false || $columns === null) {
                $prefixedTable = $db->prefixTable('users');
                if ($prefixedTable !== 'users') {
                    $columns = $db->getFieldNames($prefixedTable);
                }
            }

            $this->userColumns = is_array($columns) ? $columns : [];
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

    /**
     * Ensure session directory exists and is writable
     */
    private function ensureSessionDirectory(): bool
    {
        $sessionPath = WRITEPATH . 'session';

        if (! is_dir($sessionPath)) {
            if (! mkdir($sessionPath, 0700, true)) {
                return false;
            }
        }

        return is_writable($sessionPath);
    }

    /**
     * Log error message
     */
    private function logError(string $message, array $context = []): void
    {
        log_message('error', '[AUTH] ' . $message . ' | ' . json_encode($context));
    }

    /**
     * Log info message
     */
    private function logInfo(string $message, array $context = []): void
    {
        log_message('info', '[AUTH] ' . $message . ' | ' . json_encode($context));
    }
}
