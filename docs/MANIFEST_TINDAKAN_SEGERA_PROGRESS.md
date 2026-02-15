# Manifest Tindakan Segera - Progress

Status: Fase 1 Pembersihan  
Tanggal pembaruan: 16 Februari 2026

## 1. Aktivasi Routing & Sidebar
- [x] Tambah route group `dokumen` di `app/Config/Routes.php`
- [x] Tambah route group `kegiatan` di `app/Config/Routes.php`
- [x] Tambah route group `laporan` di `app/Config/Routes.php`
- [x] Tambah route `monitoring` di `app/Config/Routes.php`
- [x] Definisikan route `post('register')` dan `get('register')`
- [x] Tambah menu sidebar `Dokumen` -> `/dokumen`
- [x] Tambah menu sidebar `Kegiatan` -> `/kegiatan`
- [x] Tambah menu sidebar `Laporan` -> `/laporan`
- [x] Tambah menu sidebar `Monitoring` -> `/monitoring`

## 2. Perbaikan CRUD (Tanda Terima & Uji Petik)

### A. Modul Tanda Terima
- [x] `edit($id)` di `app/Controllers/TandaTerima.php`
- [x] `update($id)` di `app/Controllers/TandaTerima.php`
- [x] Buat view `app/Views/tandaterima/edit.php`
- [x] Tambah tombol `Edit` pada `app/Views/tandaterima/index.php`

### B. Modul Uji Petik
- [x] `edit($id)` di `app/Controllers/UjiPetik.php`
- [x] `update($id)` di `app/Controllers/UjiPetik.php`
- [x] Buat view `app/Views/uji_petik/edit.php`
- [x] Tambah tombol `Edit` pada `app/Views/uji_petik/index.php`

## 3. Inisialisasi Logistik
- [x] Hapus placeholder dan siapkan daftar barang di `app/Controllers/Logistik.php`
- [x] Buat migration `app/Database/Migrations/2026-02-15-173500_CreateLogistikTable.php`
- [x] Buat model `app/Models/LogistikModel.php`
- [x] Siapkan view daftar inventaris `app/Views/logistik/index.php`

## Catatan Verifikasi
- [x] Route modul baru muncul pada `php spark routes`
- [x] Edit route muncul: `tanda-terima/edit/{id}` dan `uji-petik/edit/{id}`
- [x] Register route aktif: `GET /register`, `POST /register`
- [x] Migration logistik terdaftar di `php spark migrate:status`
