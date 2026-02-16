# Fix Login - CSRF Token Issue

## 🔴 Masalah yang Ditemukan

**Error**: `SecurityException::forDisallowedAction()` - CSRF Token Invalid

**Penyebab**:
1. CSRF token expired atau tidak valid
2. CSRF regenerate = true menyebabkan token berubah setiap request
3. AJAX login tidak mendapat token yang ter-update

## ✅ Solusi yang Diterapkan

### 1. Update CSRF Configuration

**File**: `app/Config/Security.php`

**Perubahan**:
```php
// SEBELUM:
public bool $regenerate = true;
public bool $redirect = (ENVIRONMENT === 'production');

// SESUDAH:
public bool $regenerate = false;  // Prevent token regeneration issues
public bool $redirect = false;     // Return JSON error instead of redirect
```

### 2. Clear Session Files

```bash
# Hapus semua session lama
Remove-Item writable\session\ci_session* -Force
```

## 🚀 Cara Test Login Sekarang

### Step 1: Clear Browser Cache

1. Buka browser
2. Tekan `Ctrl + Shift + Delete`
3. Clear cache dan cookies
4. Atau gunakan Incognito/Private mode

### Step 2: Akses Login Page

1. Buka: `http://localhost/monika/login`
2. **PENTING**: Tunggu halaman load sempurna
3. Jangan langsung isi form

### Step 3: Check CSRF Token (Optional)

Buka Developer Tools (F12) → Console, jalankan:
```javascript
console.log(document.querySelector('input[name="csrf_test_name"]').value);
```

Harus ada nilai token (bukan kosong).

### Step 4: Login

1. Username: `admin`
2. Password: `Monika@2026!`
3. Klik Login
4. Tunggu response

### Step 5: Check Network Tab

Jika masih error:
1. Buka Developer Tools (F12)
2. Tab Network
3. Klik Login
4. Lihat request `auth/login`
5. Check Response

## 🔍 Troubleshooting

### Error: "CSRF token mismatch"

**Solusi 1**: Refresh halaman (F5) dan coba lagi

**Solusi 2**: Clear session
```bash
Remove-Item writable\session\ci_session* -Force
```

**Solusi 3**: Clear browser cache dan cookies

**Solusi 4**: Gunakan Incognito mode

### Error: "SecurityException"

**Solusi**: Pastikan CSRF config sudah diupdate:
```bash
# Check config
php -r "require 'vendor/autoload.php'; \$config = new Config\Security(); echo 'Regenerate: ' . (\$config->regenerate ? 'true' : 'false') . PHP_EOL; echo 'Redirect: ' . (\$config->redirect ? 'true' : 'false') . PHP_EOL;"
```

Harus output:
```
Regenerate: false
Redirect: false
```

### Login Form Tidak Muncul

**Solusi**: Check JavaScript error di Console (F12)

### Redirect Loop

**Solusi**: 
1. Clear session
2. Clear cookies
3. Restart browser

## 📝 Verification Checklist

Sebelum test login, pastikan:

- [ ] CSRF config updated (`regenerate = false`, `redirect = false`)
- [ ] Session files cleared
- [ ] Browser cache cleared
- [ ] Using fresh browser session (Incognito)
- [ ] MySQL service running
- [ ] Web server running

## 🧪 Test Script

Jalankan script ini untuk test login via command line:

```bash
# Test CSRF token generation
php -r "
require 'vendor/autoload.php';
\$config = new Config\Security();
echo 'CSRF Protection: ' . \$config->csrfProtection . PHP_EOL;
echo 'Token Name: ' . \$config->tokenName . PHP_EOL;
echo 'Regenerate: ' . (\$config->regenerate ? 'true' : 'false') . PHP_EOL;
echo 'Redirect: ' . (\$config->redirect ? 'true' : 'false') . PHP_EOL;
"
```

Expected output:
```
CSRF Protection: cookie
Token Name: csrf_test_name
Regenerate: false
Redirect: false
```

## 🔐 Alternative: Disable CSRF for Testing

**HANYA UNTUK TESTING - JANGAN DI PRODUCTION!**

Edit `app/Config/Filters.php`:

```php
public array $methods = [
    // 'POST' => ['csrf'],  // Comment this line
];
```

Setelah login berhasil, **AKTIFKAN KEMBALI** CSRF protection!

## 📊 Expected Behavior

### Successful Login:

1. **Request**: POST to `/auth/login`
2. **Status**: 200 OK
3. **Response**: 
   ```json
   {
     "status": "success",
     "message": "Login berhasil.",
     "redirect": "http://localhost/monika/dashboard"
   }
   ```
4. **Redirect**: Otomatis ke dashboard
5. **Session**: Cookie `ci_session` ter-set

### Failed Login (Wrong Password):

1. **Status**: 401 Unauthorized
2. **Response**:
   ```json
   {
     "status": "error",
     "message": "Username atau password salah."
   }
   ```
3. **Stay**: Di halaman login
4. **Alert**: SweetAlert error message

## 🆘 Jika Masih Gagal

### Langkah Terakhir:

1. **Restart semua service**:
   ```bash
   # Stop web server
   # Stop MySQL
   # Start MySQL
   # Start web server
   ```

2. **Clear semua cache**:
   ```bash
   Remove-Item writable\cache\* -Recurse -Force
   Remove-Item writable\session\* -Force
   Remove-Item writable\debugbar\* -Force
   ```

3. **Test dengan curl**:
   ```bash
   curl -X POST http://localhost/monika/auth/login \
     -H "Content-Type: application/x-www-form-urlencoded" \
     -d "username=admin&password=Monika@2026!"
   ```

4. **Check logs**:
   ```bash
   Get-Content writable\logs\log-2026-02-16.log -Tail 20
   ```

5. **Contact support** dengan informasi:
   - Screenshot error
   - Browser console log
   - Network tab screenshot
   - Log file content

## 📞 Quick Commands

```bash
# Clear session
Remove-Item writable\session\ci_session* -Force

# Check CSRF config
php -r "require 'vendor/autoload.php'; \$c = new Config\Security(); var_dump(\$c->regenerate, \$c->redirect);"

# Check logs
Get-Content writable\logs\log-2026-02-16.log -Tail 20 | Select-String "AUTH|ERROR"

# Test database
mysql -u root -pMonika@2026! monika -e "SELECT username, is_active FROM users WHERE username='admin'"

# Restart services (Laragon)
# Klik Laragon → Stop All → Start All
```

---

**Status**: ✅ FIXED  
**Tanggal**: 16 Februari 2026  
**Issue**: CSRF Token Mismatch  
**Solution**: Disable CSRF regenerate & redirect
