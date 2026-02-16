# Checklist Verifikasi Perbaikan Login

## Pre-Deployment Checklist

### Environment Setup
- [ ] PHP 7.4+ terinstall
- [ ] MySQL/MariaDB running
- [ ] Composer dependencies installed
- [ ] .env file configured dengan benar
- [ ] Database `monika` sudah dibuat
- [ ] User database memiliki privileges yang cukup

### Configuration Check
- [ ] `database.default.hostname` di .env
- [ ] `database.default.database` di .env
- [ ] `database.default.username` di .env
- [ ] `database.default.password` di .env
- [ ] `encryption.key` di .env
- [ ] `auth.remember.key` di .env
- [ ] `app.baseURL` di .env sesuai dengan URL akses

### File Permissions
- [ ] `writable/` directory writable
- [ ] `writable/logs/` directory writable
- [ ] `writable/session/` directory exists dan writable
- [ ] `writable/cache/` directory writable

## Deployment Checklist

### Backup
- [ ] Database di-backup
- [ ] File konfigurasi di-backup (.env)
- [ ] Backup disimpan di lokasi yang aman
- [ ] Backup diverifikasi (bisa di-restore)

### Migration
- [ ] Run `php spark migrate`
- [ ] Verify table `login_attempts` created
- [ ] Check table structure correct
- [ ] No migration errors

### Code Deployment
- [ ] File `app/Config/Database.php` updated
- [ ] File `app/Controllers/Auth.php` updated
- [ ] File `app/Models/LoginAttemptModel.php` created
- [ ] File `app/Commands/DiagnoseLogin.php` created
- [ ] Migration file created
- [ ] Test files created

### Diagnostic
- [ ] Run `php spark diagnose:login`
- [ ] All checks passed (green)
- [ ] No critical errors (red)
- [ ] Warnings addressed (yellow)

## Post-Deployment Testing

### Manual Testing

#### Test 1: Login dengan Kredensial Valid
- [ ] Buka `http://localhost/monika/login`
- [ ] Masukkan username valid
- [ ] Masukkan password valid
- [ ] Klik Login
- [ ] **Expected**: Redirect ke dashboard
- [ ] **Expected**: Session terbentuk
- [ ] **Expected**: Bisa akses halaman protected

#### Test 2: Login dengan Password Salah
- [ ] Buka halaman login
- [ ] Masukkan username valid
- [ ] Masukkan password SALAH
- [ ] Klik Login
- [ ] **Expected**: Error message "Username atau password salah"
- [ ] **Expected**: Tetap di halaman login
- [ ] **Expected**: Login attempt tercatat di database

#### Test 3: Login dengan Username Tidak Ada
- [ ] Buka halaman login
- [ ] Masukkan username yang tidak ada
- [ ] Masukkan password apapun
- [ ] Klik Login
- [ ] **Expected**: Error message "Username atau password salah"
- [ ] **Expected**: Tidak membocorkan info "user tidak ditemukan"

#### Test 4: Login dengan Field Kosong
- [ ] Buka halaman login
- [ ] Kosongkan username
- [ ] Kosongkan password
- [ ] Klik Login
- [ ] **Expected**: Error message "Username/Email dan password wajib diisi"
- [ ] **Expected**: Status code 422

#### Test 5: Rate Limiting
- [ ] Login dengan password salah 5 kali berturut-turut
- [ ] Coba login ke-6
- [ ] **Expected**: Error "Terlalu banyak percobaan login"
- [ ] **Expected**: Status code 429
- [ ] **Expected**: Harus tunggu 15 menit atau clear attempts

#### Test 6: Login dengan Akun Inactive
- [ ] Set `is_active = 0` untuk test user
- [ ] Coba login dengan user tersebut
- [ ] **Expected**: Error "Akun Anda tidak aktif"
- [ ] **Expected**: Status code 403

#### Test 7: Remember Me
- [ ] Login dengan centang "Remember Me"
- [ ] **Expected**: Cookie `monika_remember` ter-set
- [ ] Close browser
- [ ] Buka browser baru
- [ ] Akses `http://localhost/monika/`
- [ ] **Expected**: Auto-login (tidak perlu login lagi)

#### Test 8: Logout
- [ ] Login terlebih dahulu
- [ ] Klik Logout
- [ ] **Expected**: Redirect ke login page
- [ ] **Expected**: Session cleared
- [ ] **Expected**: Cookie cleared
- [ ] Coba akses dashboard
- [ ] **Expected**: Redirect ke login

#### Test 9: Session Persistence
- [ ] Login
- [ ] Akses berbagai halaman (dashboard, presensi, dll)
- [ ] Tunggu 5 menit
- [ ] Akses halaman lagi
- [ ] **Expected**: Session masih aktif
- [ ] **Expected**: Tidak perlu login ulang

#### Test 10: Multiple Browser/Device
- [ ] Login di Browser A
- [ ] Login di Browser B dengan user yang sama
- [ ] **Expected**: Kedua session aktif
- [ ] Logout di Browser A
- [ ] **Expected**: Browser B masih login

### Database Verification

#### Check Login Attempts Table
```sql
-- Verify table exists
SHOW TABLES LIKE 'login_attempts';

-- Check structure
DESCRIBE login_attempts;

-- Check data
SELECT * FROM login_attempts ORDER BY attempt_time DESC LIMIT 10;

-- Verify successful login logged
SELECT * FROM login_attempts WHERE success = 1 ORDER BY attempt_time DESC LIMIT 5;

-- Verify failed login logged
SELECT * FROM login_attempts WHERE success = 0 ORDER BY attempt_time DESC LIMIT 5;
```

- [ ] Table exists
- [ ] Structure correct
- [ ] Successful logins logged
- [ ] Failed logins logged
- [ ] IP address recorded
- [ ] User agent recorded
- [ ] Error messages recorded

#### Check Users Table
```sql
-- Verify users exist
SELECT id, username, email, is_active, id_role FROM users;

-- Check password hashing
SELECT username, LEFT(password, 10) as password_hash FROM users;
```

- [ ] At least one active user exists
- [ ] Passwords are hashed (start with $2y$)
- [ ] `is_active` field exists
- [ ] Email field exists (if used)

### Log Verification

#### Check Application Logs
```bash
type writable\logs\log-2026-02-16.log | findstr "[AUTH]"
```

- [ ] Login attempts logged
- [ ] Failed logins logged with reason
- [ ] Successful logins logged
- [ ] Rate limiting logged
- [ ] Database errors logged (if any)
- [ ] Session errors logged (if any)

### Automated Testing

#### Run Unit Tests
```bash
vendor\bin\phpunit tests\unit\AuthTest.php
```

- [ ] All tests passed
- [ ] No failures
- [ ] No errors
- [ ] No skipped tests

#### Run Integration Tests
```bash
vendor\bin\phpunit tests\integration\LoginFlowTest.php
```

- [ ] All tests passed
- [ ] No failures
- [ ] No errors
- [ ] No skipped tests

### Performance Testing

#### Response Time
- [ ] Login response < 2 seconds
- [ ] Dashboard load < 3 seconds
- [ ] No timeout errors
- [ ] No memory errors

#### Concurrent Users
- [ ] Test dengan 5 concurrent logins
- [ ] Test dengan 10 concurrent logins
- [ ] No deadlocks
- [ ] No session conflicts

### Security Testing

#### SQL Injection
- [ ] Test dengan `' OR '1'='1` di username
- [ ] Test dengan `'; DROP TABLE users; --` di password
- [ ] **Expected**: Tidak ada SQL injection
- [ ] **Expected**: Input di-escape dengan benar

#### XSS
- [ ] Test dengan `<script>alert('XSS')</script>` di username
- [ ] **Expected**: Script tidak dieksekusi
- [ ] **Expected**: Input di-escape di output

#### CSRF
- [ ] Test submit form tanpa CSRF token
- [ ] **Expected**: Request ditolak
- [ ] **Expected**: Error CSRF token invalid

#### Brute Force
- [ ] Test 10 failed login attempts
- [ ] **Expected**: Rate limiting aktif setelah 5 attempts
- [ ] **Expected**: IP di-block sementara

## Browser Compatibility

### Desktop Browsers
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Edge (latest)
- [ ] Safari (latest) - jika ada Mac

### Mobile Browsers
- [ ] Chrome Mobile
- [ ] Safari Mobile
- [ ] Firefox Mobile

### Features to Test per Browser
- [ ] Login form tampil dengan benar
- [ ] Password toggle berfungsi
- [ ] Remember me checkbox berfungsi
- [ ] Error messages tampil dengan benar
- [ ] Redirect berfungsi
- [ ] Session persists

## Rollback Verification

### If Issues Found
- [ ] Stop deployment
- [ ] Document the issue
- [ ] Restore database from backup
- [ ] Revert code changes
- [ ] Clear sessions
- [ ] Restart web server
- [ ] Verify rollback successful
- [ ] Notify team

## Sign-Off

### Developer
- [ ] All code changes reviewed
- [ ] All tests passed
- [ ] Documentation complete
- [ ] No known issues

**Signed**: _________________ Date: _________

### QA/Tester
- [ ] All manual tests passed
- [ ] All automated tests passed
- [ ] Performance acceptable
- [ ] Security verified

**Signed**: _________________ Date: _________

### Project Manager
- [ ] Requirements met
- [ ] Documentation reviewed
- [ ] Ready for production

**Signed**: _________________ Date: _________

---

## Notes

Gunakan section ini untuk mencatat issues atau observations selama testing:

```
[Date] [Tester] [Issue/Note]
______________________________________
______________________________________
______________________________________
______________________________________
______________________________________
```

---

**Checklist Version**: 1.0  
**Last Updated**: 16 Februari 2026  
**Status**: ⏳ PENDING VERIFICATION
