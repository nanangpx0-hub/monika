# Perbaikan Directory Listing - MONIKA

## Masalah
Akses `http://localhost/monika/` menampilkan directory listing, bukan website CodeIgniter.

## Perbaikan yang Dilakukan

### 1. ✅ File `.htaccess` di Root (MONIKA/)
**Lokasi:** `.htaccess`

**Fungsi:** Mengarahkan semua request ke folder `public/`

```apache
# Disable directory browsing
Options -Indexes

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /monika/

    # Redirect to lowercase URL (optional)
    RewriteCond %{REQUEST_URI} ^/MONIKA(/.*)?$ [NC]
    RewriteRule ^ /monika%1 [R=301,L]

    # Redirect everything to public folder
    RewriteCond %{REQUEST_URI} !^/monika/public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

# Prevent access to hidden files
<FilesMatch "^\.">
    <IfModule mod_authz_core.c>
        Require all denied
    </IfModule>
    <IfModule !mod_authz_core.c>
        Order allow,deny
        Deny from all
    </IfModule>
</FilesMatch>
```

### 2. ✅ Konfigurasi `app/Config/App.php`

**Perubahan:**
- `$baseURL` diubah dari `'http://localhost:8080/'` → `'http://localhost/monika/'`
- `$indexPage` diubah dari `'index.php'` → `''` (string kosong)

```php
public string $baseURL = 'http://localhost/monika/';
public string $indexPage = '';
```

### 3. ✅ File `.htaccess` di Folder `public/`
**Status:** Sudah benar, tidak perlu diubah

## Cara Test

1. **Restart Apache** (jika menggunakan Laragon/XAMPP)
2. **Akses URL:**
   - `http://localhost/monika/` → Harus redirect ke halaman aplikasi
   - `http://localhost/monika/login` → Halaman login
   - `http://localhost/monika/dashboard` → Dashboard

## Jika Masih Bermasalah

### Cek Module Apache
Pastikan `mod_rewrite` aktif:

**Laragon:**
1. Menu Laragon → Apache → httpd.conf
2. Cari: `LoadModule rewrite_module modules/mod_rewrite.so`
3. Pastikan tidak ada `#` di depannya
4. Restart Apache

### Cek AllowOverride
Di `httpd.conf`, pastikan:
```apache
<Directory "E:/laragon/www">
    AllowOverride All
</Directory>
```

### Test .htaccess Terbaca
Tambahkan baris error di `.htaccess`:
```
TESTING_ERROR
```
Jika muncul error 500, berarti `.htaccess` terbaca. Hapus baris tersebut.

### Akses Langsung ke Public
Test: `http://localhost/monika/public/`
- Jika berhasil → Masalah di `.htaccess` root
- Jika gagal → Masalah di konfigurasi CodeIgniter

## File yang Diubah
1. `.htaccess` (root folder)
2. `app/Config/App.php`

## Dokumentasi Lengkap
Lihat: `docs/HTACCESS_TROUBLESHOOTING.md`

---
**Tanggal:** 2026-02-15
**Status:** ✅ SELESAI
