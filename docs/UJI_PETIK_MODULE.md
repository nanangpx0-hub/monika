# Modul Uji Petik Kualitas - MONIKA

## Deskripsi
Modul Uji Petik Kualitas digunakan oleh Tim Statistik Sosial untuk membandingkan isian dokumen fisik (K) dengan isian komputer (C) guna memastikan kualitas data entry.

## Fitur Utama

### 1. Daftar Temuan Uji Petik
- Menampilkan semua temuan kesalahan entry
- Kolom Isian K ditampilkan dengan warna hijau (dokumen fisik yang benar)
- Kolom Isian C ditampilkan dengan warna merah (isian komputer yang salah)
- Informasi lengkap: NKS, Ruta, Variabel, Alasan Kesalahan, dan Catatan
- Fitur hapus temuan

### 2. Tambah Temuan Baru
- Form input dengan validasi lengkap
- Dropdown NKS dari master data
- Input nomor ruta (1-10)
- Input nama variabel yang diperiksa
- Input isian K (dokumen fisik) dan isian C (komputer)
- Dropdown alasan kesalahan:
  - Salah Ketik
  - Salah Baca
  - Terlewat
  - Salah Kode
  - Lainnya
- Textarea catatan (opsional)

## Struktur Database

### Tabel: `uji_petik`
```sql
- id (INT, AI, PK)
- nks (VARCHAR 10)
- no_ruta (INT, 1-10)
- variabel (VARCHAR 100)
- isian_k (VARCHAR 255) -- Isian Dokumen Fisik
- isian_c (VARCHAR 255) -- Isian Komputer
- alasan_kesalahan (ENUM)
- catatan (TEXT, Nullable)
- created_at (DATETIME)
- updated_at (DATETIME)
```

## File yang Dibuat

### 1. Migration
- `app/Database/Migrations/2026-02-15-170528_CreateUjiPetikTable.php`

### 2. Model
- `app/Models/UjiPetikModel.php`
  - Method `getAllWithNks()`: Mengambil data dengan join ke nks_master

### 3. Controller
- `app/Controllers/UjiPetik.php`
  - `index()`: Menampilkan daftar temuan
  - `new()`: Form tambah temuan
  - `store()`: Menyimpan temuan baru
  - `delete($id)`: Menghapus temuan

### 4. Views
- `app/Views/uji_petik/index.php`: Tabel daftar temuan
- `app/Views/uji_petik/new.php`: Form input temuan

### 5. Routing
```php
$routes->group('uji-petik', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'UjiPetik::index');
    $routes->get('new', 'UjiPetik::new');
    $routes->post('store', 'UjiPetik::store');
    $routes->get('delete/(:num)', 'UjiPetik::delete/$1');
});
```

## Cara Penggunaan

### Menambah Temuan Uji Petik
1. Akses menu "Uji Petik Kualitas"
2. Klik tombol "Tambah Temuan"
3. Pilih NKS dan nomor ruta
4. Masukkan nama variabel yang diperiksa
5. Masukkan isian K (dokumen fisik yang benar)
6. Masukkan isian C (isian komputer yang salah)
7. Pilih alasan kesalahan
8. Tambahkan catatan jika diperlukan
9. Klik "Simpan Temuan"

### Menghapus Temuan
1. Pada tabel daftar temuan, klik tombol hapus (ikon trash)
2. Konfirmasi penghapusan

## Validasi
- NKS: Wajib diisi
- No Ruta: Wajib diisi, angka 1-10
- Variabel: Wajib diisi, maksimal 100 karakter
- Isian K: Wajib diisi, maksimal 255 karakter
- Isian C: Wajib diisi, maksimal 255 karakter
- Alasan Kesalahan: Wajib dipilih dari list yang tersedia

## Integrasi
- Menggunakan layout AdminLTE yang sama dengan modul lain
- Terintegrasi dengan sistem autentikasi (filter 'auth')
- Menggunakan CSRF protection untuk form submission
- Join dengan tabel `nks_master` untuk informasi Kecamatan/Desa

## Catatan Teknis
- Menggunakan DataTables untuk tabel yang responsif
- Flashdata untuk notifikasi sukses/error
- Soft validation dengan pesan error yang informatif
- Timestamps otomatis untuk tracking created_at dan updated_at
