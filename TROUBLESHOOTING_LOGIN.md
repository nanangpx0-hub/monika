# Troubleshooting Login - Sistem MONIKA

## Quick Diagnostic

Jalankan command berikut untuk diagnosa otomatis:

```bash
php spark diagnose:login
```

## Common Issues & Solutions

### 1. "Silakan login terlebih dahulu" setelah login berhasil

**Penyebab**: Session tidak terbentuk atau hilang setelah login.

**Solusi**:

#### A. Periksa Session Directory
```bash
# Windows CMD
dir writable\session

# Jika tidak ada, buat folder:
mkdir writable\session

# Set permission (di Windows, pastikan folder tidak read-only)
```

#### B. Periksa Database Connection
```bash
# Test koneksi database
php spark db:table users
```

Jika error, periksa file `.env`:
```env
database.default.hostname = localhost
database.default.database = monika
database.default.username = root
database.default.password = Monika@2026!
```

#### C. Clear Session Files
```bash
# Hapus semua session lama
del writable\session\ci_session*
```

### 2. "Username atau password salah" padahal kredensial benar

**Penyebab**: Password di database tidak ter-hash dengan benar.

**Solusi**:

```bash
# Re-seed user dengan password yang benar
php spark db:seed UserSeeder
```

Atau update manual via SQL:
```sql
UPDATE users 
SET password = '$2y$10$...' -- hasil dari password_hash('password', PASSWORD_BCRYPT)
WHERE username = 'admin_nanang';
```

### 3. "Terlalu banyak percobaan login"

**Penyebab**: Rate limiting aktif setelah 5 kali gagal login.

**Solusi**:

```bash
# Tunggu 15 menit, atau clear login attempts:
php spark db:query "DELETE FROM login_attempts WHERE ip_address = 'YOUR_IP'"
```

### 4. "Akun Anda tidak aktif"

**Penyebab**: Field `is_active` di database bernilai 0.

**Solusi**:

```sql
UPDATE users 
SET is_active = 1 
WHERE username = 'your_username';
```

### 5. Session hilang setelah redirect

**Penyebab**: Cookie tidak ter-set dengan benar.

**Solusi**:

Periksa `app/Config/Session.php`:
```php
public string $cookieName = 'monika_session'; // Ganti dari default
public bool $matchIP = false; // Pastikan false
public int $expiration = 7200; // 2 jam
```

Periksa `app/Config/App.php`:
```php
public string $baseURL = 'http://localhost/monika/'; // Harus sesuai URL akses
public string $cookieDomain = ''; // Kosongkan untuk localhost
public string $cookiePath = '/'; // Root path
public bool $cookieSecure = false; // False untuk development
public string $cookieSameSite = 'Lax'; // Lax untuk compatibility
```

### 6. Database connection error

**Penyebab**: MySQL service tidak running atau kredensial salah.

**Solusi**:

```bash
# Cek MySQL service
net start | findstr MySQL

# Jika tidak running, start service:
net start MySQL80  # Sesuaikan dengan nama service Anda

# Test koneksi manual:
mysql -u root -p
# Masukkan password: Monika@2026!
```

### 7. CSRF Token Mismatch

**Penyebab**: CSRF token expired atau tidak valid.

**Solusi**:

- Refresh halaman login (F5)
- Clear browser cache
- Pastikan CSRF regenerate di `app/Config/Security.php`:

```php
public bool $regenerate = true;
public string $redirect = false; // Jangan redirect otomatis
```

### 8. Remember Me tidak berfungsi

**Penyebab**: Encryption key tidak ter-set.

**Solusi**:

Periksa file `.env`:
```env
auth.remember.key = monika-remember-secret-key-change-in-production
```

Jika tidak ada, tambahkan key tersebut.

## Debugging Steps

### 1. Enable Debug Mode

Edit `.env`:
```env
CI_ENVIRONMENT = development
```

Edit `app/Config/Logger.php`:
```php
public int $threshold = 4; // Log semua level
```

### 2. Check Logs

```bash
# Lihat log terbaru
type writable\logs\log-2026-02-16.log
```

Cari error dengan keyword:
- `[AUTH]` - Auth-related errors
- `[ERROR]` - General errors
- `Session` - Session issues

### 3. Test Database Query

```bash
php spark db:query "SELECT * FROM users WHERE username = 'admin_nanang'"
```

### 4. Test Session Manually

Buat file `test_session.php` di root:
```php
<?php
require 'vendor/autoload.php';

$session = \Config\Services::session();
$session->start();
$session->set('test', 'value');

echo "Session ID: " . session_id() . "\n";
echo "Test value: " . $session->get('test') . "\n";
echo "Session path: " . ini_get('session.save_path') . "\n";
```

Jalankan:
```bash
php test_session.php
```

### 5. Check Browser Console

Buka Developer Tools (F12) → Console tab

Cari error JavaScript yang mungkin memblokir login flow.

### 6. Check Network Tab

Buka Developer Tools (F12) → Network tab

- Klik tombol Login
- Periksa request ke `/auth/login`
- Lihat Response:
  - Status code harus 200
  - Response JSON harus `{"status":"success"}`
  - Cookie `ci_session` harus ter-set

## Prevention Checklist

Sebelum deploy atau setelah setup baru:

- [ ] Database connection tested
- [ ] Session directory exists and writable
- [ ] Encryption keys set in .env
- [ ] Base URL configured correctly
- [ ] At least one active user exists
- [ ] Migrations run successfully
- [ ] Logs directory writable
- [ ] CSRF protection enabled
- [ ] Rate limiting configured

## Getting Help

Jika masalah masih berlanjut:

1. Jalankan diagnostic: `php spark diagnose:login`
2. Kumpulkan informasi:
   - Error message lengkap
   - Log file terbaru
   - Browser console errors
   - Network request/response
3. Periksa dokumentasi: `ANALISIS_MASALAH_LOGIN.md`
4. Hubungi tim development dengan informasi di atas

## Emergency Access

Jika tidak bisa login sama sekali, buat user baru via command:

```bash
php spark db:query "INSERT INTO users (username, password, email, fullname, id_role, is_active, created_at, updated_at) VALUES ('emergency', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'emergency@monika.local', 'Emergency User', 1, 1, NOW(), NOW())"
```

Login dengan:
- Username: `emergency`
- Password: `password`

**PENTING**: Hapus user ini setelah masalah teratasi!

---

**Last Updated**: 16 Februari 2026
