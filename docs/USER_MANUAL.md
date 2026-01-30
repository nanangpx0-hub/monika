# PANDUAN PENGGUNA SISTEM MONIKA
## Sistem Monitoring dan Manajemen Kegiatan Survey

**Versi 1.0 | Januari 2026**

---

## Daftar Isi

1. [Pendahuluan](#pendahuluan)
2. [Akses Sistem](#akses-sistem)
3. [Manajemen PCL](#manajemen-pcl)
4. [Manajemen PML](#manajemen-pml)
5. [Manajemen User](#manajemen-user)
6. [Audit Trail](#audit-trail)
7. [API Documentation](#api-documentation)

---

## 1. Pendahuluan

MONIKA adalah Sistem Monitoring dan Manajemen Kegiatan Survey yang dirancang untuk mengelola:
- **PCL** (Petugas Pendataan Lapangan)
- **PML** (Petugas Monitoring Lapangan)
- **User Sistem** dengan berbagai hak akses

### Fitur Utama
- ✅ CRUD lengkap untuk semua entitas
- ✅ Import/Export data dalam format CSV
- ✅ Laporan kinerja dengan visualisasi grafik
- ✅ Audit trail untuk semua aktivitas
- ✅ API RESTful dengan autentikasi JWT

---

## 2. Akses Sistem

### Login
1. Buka aplikasi di browser
2. Masukkan **Username** atau **Email**
3. Masukkan **Password**
4. Klik tombol **Login**

### Role Pengguna

| Role | ID | Hak Akses |
|------|-----|-----------|
| Super Admin | 1 | Akses penuh ke semua fitur |
| Viewer | 2 | Hanya dapat melihat data |
| PCL | 3 | Pendataan lapangan |
| Pengolah | 4 | Pengolahan data |
| PML | 5 | Monitoring lapangan |

---

## 3. Manajemen PCL

### Melihat Daftar PCL
1. Login sebagai **Admin**
2. Navigasi ke **Admin → Manajemen PCL**
3. Gunakan filter untuk menyaring berdasarkan:
   - Wilayah kerja
   - Status (Aktif/Non-Aktif)

### Menambah PCL Baru
1. Klik tombol **+ Tambah PCL**
2. Isi form registrasi:
   - Nama Lengkap (wajib)
   - Username (wajib, unik)
   - Email (wajib, unik)
   - Password (wajib, minimal 6 karakter)
   - NIK KTP (wajib, 16 digit)
   - Nomor Telepon (wajib)
   - Wilayah Kerja (wajib)
   - Masa Tugas (opsional)
3. Klik **Simpan**

### Import Data PCL dari CSV
1. Klik tombol **Impor CSV**
2. Download template CSV terlebih dahulu
3. Isi data sesuai format template:
   - Nama Lengkap
   - Username
   - Email
   - NIK (16 digit)
   - No Telp
   - Wilayah Kerja
   - SOBAT ID (opsional)
   - Masa Tugas Mulai (format: YYYY-MM-DD)
   - Masa Tugas Selesai (format: YYYY-MM-DD)
4. Upload file CSV
5. Klik **Import Data**
6. Password default: `pcl12345` (wajib diganti setelah login)

### Export Data PCL
1. Klik tombol **Ekspor CSV**
2. File akan terunduh otomatis

---

## 4. Manajemen PML

### Melihat Daftar PML
1. Navigasi ke **Admin → Manajemen PML**
2. Lihat daftar PML dengan pagination

### Menambah PML Baru
1. Klik tombol **+ Tambah PML**
2. Isi form:
   - Data identitas
   - Kualifikasi
   - Wilayah Supervisi
3. Klik **Simpan**

### Mapping PCL ke PML
1. Buka halaman **Edit PML**
2. Di bagian "PCL yang Dibina", pilih PCL dari daftar
3. Klik **Tugaskan PCL**
4. Untuk melepas PCL, klik tombol **Lepas** di samping nama PCL

### Laporan Kinerja PML
1. Pada daftar PML, klik ikon **📊 Laporan Kinerja**
2. Lihat:
   - Summary aktivitas (total, bulan ini, pertumbuhan)
   - Grafik aktivitas bulanan
   - Statistik PCL yang dibina
   - Riwayat aktivitas terakhir
3. Klik **Ekspor Laporan** untuk download CSV

### Mencatat Aktivitas Monitoring
1. Buka halaman **Laporan Kinerja PML**
2. Di bagian "Catat Aktivitas Baru":
   - Pilih jenis aktivitas
   - Masukkan deskripsi
   - (Opsional) Klik **GPS** untuk mengambil koordinat lokasi
3. Klik **Simpan Aktivitas**

---

## 5. Manajemen User

### Melihat Daftar User
1. Navigasi ke **Admin → Manajemen User**
2. Gunakan kotak pencarian untuk mencari user
3. Klik **Edit** untuk mengubah data user

### Menambah User Baru
1. Klik **+ Tambah User**
2. Isi form:
   - Nama Lengkap
   - Username (unik)
   - Email (unik)
   - Password & Konfirmasi Password
   - Pilih Role
   - Data tambahan sesuai role
3. Klik **Simpan**

### Reset Password User
1. Pada daftar user, klik ikon **🔑 Reset Password**
2. Pilih salah satu:
   - Masukkan password baru secara manual
   - Centang "Generate password otomatis"
3. Klik **Reset Password**
4. Password baru akan ditampilkan (jika auto-generate)
5. Informasikan password baru ke user terkait

### Mengaktifkan/Menonaktifkan User
1. Pada daftar user, klik tombol toggle status
2. Status user akan berubah antara Aktif/Non-Aktif
3. User non-aktif tidak dapat login ke sistem

---

## 6. Audit Trail

### Melihat Log Aktivitas
1. Navigasi ke **Admin → Audit Trail**
2. Lihat ringkasan:
   - Aktivitas hari ini
   - Aktivitas 7 hari terakhir
   - Total aktivitas

### Filter Log
1. Klik panel **Filter & Pencarian**
2. Filter berdasarkan:
   - User tertentu
   - Jenis aksi
   - Rentang tanggal
   - Kata kunci pencarian
3. Klik tombol **Search**

### Export Log Audit
1. Klik tombol **Ekspor CSV**
2. Filter yang aktif akan diterapkan ke file export

---

## 7. API Documentation

### Mengakses Dokumentasi API
1. Buka URL: `http://[domain]/api/docs`
2. Lihat dokumentasi Swagger UI

### Autentikasi API
1. Kirim request POST ke `/api/auth/login`
2. Body:
```json
{
  "login_id": "username",
  "password": "password123"
}
```
3. Simpan token dari response
4. Gunakan token di header: `Authorization: Bearer {token}`

### Endpoint yang Tersedia
- `GET /api/users` - Daftar semua user
- `GET /api/users/{id}` - Detail user
- `POST /api/users` - Tambah user baru
- `PUT /api/users/{id}` - Update user
- `DELETE /api/users/{id}` - Hapus user

---

## Troubleshooting

### Tidak Bisa Login
- Pastikan username/email dan password benar
- Pastikan akun dalam status **Aktif**
- Hubungi Admin untuk reset password jika lupa

### Import CSV Gagal
- Pastikan format file adalah CSV (UTF-8)
- Periksa apakah semua field wajib terisi
- Pastikan tidak ada username/email duplikat
- Periksa format NIK (16 digit angka)
- Periksa format tanggal (YYYY-MM-DD)

### Error "Akses Ditolak"
- Pastikan role Anda memiliki hak akses ke halaman tersebut
- Hubungi Admin untuk menambah hak akses

---

## Kontak Support

Jika mengalami kendala, hubungi:
- **Email**: support@bps.go.id
- **Telepon**: (031) 123-4567

---

*Dokumen ini terakhir diperbarui: Januari 2026*
