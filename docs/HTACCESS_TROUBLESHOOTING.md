# Troubleshooting: Directory Listing di CodeIgniter 4

## Masalah
Ketika mengakses `http://localhost/monika/`, yang muncul adalah daftar folder dan file (Directory Listing), bukan tampilan website CodeIgniter.

## Penyebab
1. File `.htaccess` di root tidak berfungsi dengan baik
2. Konfigurasi `$baseURL` dan `$indexPage` di `app/Config/App.php` tidak sesuai
3. Module `mod_rewrite` Apache tidak aktif

## Solusi yang Diterapkan

### 1. File `.htaccess` di Root Folder
File ini mengarahkan semua request ke folder `public/`:

```apache
# Disable directory browsing
Options -Indexes

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /monika/

    # Redirect to lowercase URL (optional)
    RewriteCond %{REQUEST_URI} ^/MONIKA(/.*)?$ [NC]
    RewriteRule ^ /monika%1 [R=301,L]

    # If the request is for a file or directory that exists in root, serve it
    # But we want to redirect everything to public folder
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

### 2. Konfigurasi `app/Config/App.php`

Pastikan konfigurasi berikut sudah benar:

```php
// Base URL harus sesuai dengan lokasi project
public string $baseURL = 'http://localhost/monika/';

// Index page harus string kosong untuk clean URL
public string $indexPage = '';
```

### 3. File `.htaccess` di Folder `public/`

File ini sudah ada dan tidak perlu diubah. Isinya menangani routing CodeIgniter:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /monika/public/

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?/$1 [L,QSA]
</IfModule>
```

## Verifikasi

### 1. Cek Module Apache
Pastikan `mod_rewrite` aktif di Apache:

**Windows (Laragon/XAMPP):**
- Buka `httpd.conf`
- Cari baris: `#LoadModule rewrite_module modules/mod_rewrite.so`
- Hapus tanda `#` di depannya
- Restart Apache

### 2. Cek Struktur Folder
```
monika/
├── .htaccess          ← Redirect ke public/
├── app/
├── public/
│   ├── .htaccess      ← Routing CodeIgniter
│   ├── index.php      ← Front Controller
│   └── assets/
└── writable/
```

### 3. Test Akses
- `http://localhost/monika/` → Harus redirect ke halaman login/dashboard
- `http://localhost/monika/login` → Halaman login
- `http://localhost/monika/dashboard` → Dashboard (jika sudah login)

## Troubleshooting Lanjutan

### Jika Masih Muncul Directory Listing:

1. **Cek Apache AllowOverride**
   Pastikan di `httpd.conf` atau `apache2.conf`:
   ```apache
   <Directory "E:/laragon/www">
       AllowOverride All
   </Directory>
   ```

2. **Cek File .htaccess Terbaca**
   Tambahkan error di `.htaccess` untuk test:
   ```apache
   TESTING_ERROR
   ```
   Jika muncul error 500, berarti `.htaccess` terbaca. Hapus baris test tersebut.

3. **Akses Langsung ke Public**
   Test akses: `http://localhost/monika/public/`
   Jika berhasil, masalahnya di `.htaccess` root.

4. **Gunakan PHP Built-in Server (Development)**
   ```bash
   php spark serve
   ```
   Akses: `http://localhost:8080`

### Jika Muncul Error 404:

1. Cek `app/Config/Routes.php`
2. Cek `$baseURL` di `app/Config/App.php`
3. Clear cache: `php spark cache:clear`

## Catatan Penting

- File `.htaccess` hanya bekerja di Apache
- Untuk Nginx, gunakan konfigurasi berbeda
- Untuk production, pastikan `Options -Indexes` aktif untuk keamanan
- Jangan hapus file `.htaccess` di folder `public/`
