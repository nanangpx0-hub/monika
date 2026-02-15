# Dokumentasi Teknis: Integrasi AdminLTE 3 ke MONIKA (CodeIgniter 4)

## 1. Ringkasan
Integrasi tampilan AdminLTE 3 pada aplikasi MONIKA berbasis CodeIgniter 4 mencakup:
- Pemisahan layout menjadi `header.php` (navbar), `sidebar.php` (menu kiri), `footer.php` (copyright).
- Penambahan `layout/template.php` sebagai wrapper utama menggunakan `renderSection()`.
- Pemasangan aset AdminLTE 3 lokal di `public/assets/adminlte` (folder `dist` dan `plugins`).
- Pembuatan Dashboard dengan 4 info box: Total NKS, Dokumen Masuk, Progres Entry, Sisa Hari.
- Penyesuaian routing untuk mengarahkan beranda ke Dashboard.

## 2. Lingkungan & Prasyarat
- PHP 8.2+ dan CodeIgniter 4.7 (lihat [composer.json](file:///c:/laragon/www/monika/composer.json)).
- Web server Apache (Laragon) dengan `mod_rewrite` aktif.
- Aplikasi terpasang di subfolder `C:\laragon\www\monika` dan diakses melalui `http://localhost/monika/`.

## 3. Fitur yang Dikembangkan
- Layout modular (navbar, sidebar, footer) untuk konsistensi antarmuka.
- Sidebar menampilkan logo BPS dan teks “MONIKA JEMBER”.
- Dashboard utama dengan empat info box.
- Aset AdminLTE dikelola lokal (tidak bergantung CDN).

## 4. Struktur Direktori Tambahan
```
public/
  assets/
    adminlte/
      dist/
      plugins/
    css/
      custom.css
    img/
      bps-logo.svg

app/
  Views/
    layout/
      header.php
      sidebar.php
      footer.php
      template.php
    dashboard/
      index.php

docs/
  TEKNIS_ADMINLTE_MONIKA.md   (dokumen ini)
```

## 5. Alur Kerja Implementasi
1) Unduh AdminLTE 3.2.0 dan ekstrak ke `public/assets/adminlte` (folder `dist` dan `plugins`).  
2) Buat layout: `header.php`, `sidebar.php`, `footer.php`, dan `template.php`.  
3) Buat `dashboard/index.php` yang `extends layout/template`.  
4) Update routes ke Dashboard dan pastikan baseURL benar.  
5) Uji akses `http://localhost/monika/` hingga halaman Dashboard tampil.

## 6. Konfigurasi yang Diperlukan
### 6.1. .env
Pastikan file `.env` berisi:
```ini
CI_ENVIRONMENT = development
app.baseURL = http://localhost/monika/
```

### 6.2. Apache .htaccess (subfolder)
Lihat [public/.htaccess](file:///c:/laragon/www/monika/public/.htaccess). Bagian penting:
```apache
Options -Indexes
RewriteEngine On
RewriteBase /monika/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^([\s\S]*)$ index.php/$1 [L,NC,QSA]
```

### 6.3. Routes
Beranda diarahkan ke Dashboard, dan fitur utama terdaftar di [Routes.php](file:///c:/laragon/www/monika/app/Config/Routes.php):
```php
$routes->get('/', 'Dashboard::index');
$routes->get('dashboard', 'Dashboard::index');
$routes->get('presensi', 'Presensi::index');
$routes->get('kartu-kendali', 'KartuKendali::index');
$routes->get('logistik', 'Logistik::index');
$routes->get('uji-petik', 'UjiPetik::index');
```

## 7. Kode Utama dan Penjelasan
### 7.1. Header (Navbar + CSS)
[header.php](file:///c:/laragon/www/monika/app/Views/layout/header.php) memuat CSS AdminLTE lokal dan navbar:
```php
<link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/adminlte/dist/css/adminlte.min.css') ?>">
```
Navbar menggunakan tombol pushmenu untuk membuka/tutup sidebar.

### 7.2. Sidebar (Logo BPS + Menu)
[sidebar.php](file:///c:/laragon/www/monika/app/Views/layout/sidebar.php) menampilkan logo BPS dan teks “MONIKA JEMBER”, serta menu ke fitur utama. Logo ada di [bps-logo.svg](file:///c:/laragon/www/monika/public/assets/img/bps-logo.svg).

### 7.3. Footer (JS + Copyright)
[footer.php](file:///c:/laragon/www/monika/app/Views/layout/footer.php) memuat skrip AdminLTE lokal dan menampilkan “© 2026 BPS Jember”:
```php
<script src="<?= base_url('assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/dist/js/adminlte.min.js') ?>"></script>
```

### 7.4. Template Utama
[template.php](file:///c:/laragon/www/monika/app/Views/layout/template.php) mendefinisikan wrapper dan dua section:
```php
<?= $this->renderSection('page_header') ?>
<?= $this->renderSection('content') ?>
```

### 7.5. Dashboard View
[dashboard/index.php](file:///c:/laragon/www/monika/app/Views/dashboard/index.php) `extends layout/template` dan menampilkan 4 info box:
- Total NKS (ikon daftar)
- Dokumen Masuk (ikon inbox)
- Progres Entry (ikon grafik, persentase)
- Sisa Hari (ikon jam pasir)

### 7.6. Controller
[Dashboard.php](file:///c:/laragon/www/monika/app/Controllers/Dashboard.php) memanggil view:
```php
return view('dashboard/index');
```

## 8. Cara Menjalankan & Verifikasi
1) Jalankan Apache melalui Laragon.  
2) Buka `http://localhost/monika/` → tampil Dashboard.  
3) Periksa Network tab (DevTools) untuk memastikan file CSS/JS termuat dari `/assets/adminlte/...` tanpa error 404.  
4) Klik menu di sidebar untuk memastikan routing berjalan.

## 9. Troubleshooting & Kendala yang Ditemui
- Error 500 akibat isi `.htaccess` tidak valid. Solusi: ganti dengan konfigurasi yang benar dan set `RewriteBase /monika/`.
- Aset tidak termuat di subfolder: atur `app.baseURL` di `.env` dan gunakan `base_url()` pada path aset.
- Jika URL masih menyertakan `index.php`, pastikan aturan rewrite aktif. Pengaturan `$indexPage` pada [App.php](file:///c:/laragon/www/monika/app/Config/App.php) dapat dibiarkan `index.php` ketika menggunakan aturan rewrite standar.

## 10. Rencana Lanjutan (TODO)
- [ ] Data dinamis untuk empat info box (query dari database).
- [ ] Penanda menu aktif di sidebar berdasarkan route saat ini.
- [ ] Breadcrumb dan judul halaman konsisten per modul.
- [ ] Otentikasi pengguna dan proteksi route.
- [ ] Unit test dasar untuk controller Dashboard.
- [ ] Dokumentasi akhir beserta screenshot tampilan.

## 11. Lampiran
### 11.1. Daftar Aset Penting
- CSS: `/assets/adminlte/dist/css/adminlte.min.css`
- JS: `/assets/adminlte/dist/js/adminlte.min.js`
- jQuery: `/assets/adminlte/plugins/jquery/jquery.min.js`
- Bootstrap: `/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js`
- Ikon: `/assets/adminlte/plugins/fontawesome-free/css/all.min.css`

### 11.2. Perintah Otomatis Unduh AdminLTE (PowerShell)
```powershell
$dest = \"c:\laragon\www\monika\public\assets\adminlte\"
if (-not (Test-Path $dest)) { New-Item -ItemType Directory -Path $dest -Force | Out-Null }
$zip = Join-Path $dest 'adminlte-3.2.0.zip'
$uri = 'https://github.com/ColorlibHQ/AdminLTE/archive/refs/tags/v3.2.0.zip'
Invoke-WebRequest -Uri $uri -OutFile $zip
Expand-Archive -Force -Path $zip -DestinationPath (Join-Path $dest 'tmp')
Move-Item -Force (Join-Path $dest 'tmp/AdminLTE-3.2.0/dist') (Join-Path $dest 'dist')
Move-Item -Force (Join-Path $dest 'tmp/AdminLTE-3.2.0/plugins') (Join-Path $dest 'plugins')
Remove-Item -Recurse -Force (Join-Path $dest 'tmp'); Remove-Item -Force $zip
```

---

Dokumentasi ini akan dilanjutkan esok hari. Bagian bertanda TODO masih belum selesai (mis. data dinamis, screenshot). Dengan konteks di atas, pengembang atau AI lain dapat melanjutkan pekerjaan tanpa klarifikasi tambahan.

