# Panduan Penggunaan Seeder Data Dummy Monika

## Deskripsi
File ini berisi panduan untuk menjalankan seeder data dummy pada sistem Monika.

## Persyaratan
- Sistem Monika harus sudah terinstal dengan benar
- Database 'monika' harus sudah dibuat
- Konfigurasi database di `.env` harus sudah disesuaikan

## Cara Menjalankan Seeder

### 1. Melalui Command Line (Direkomendasikan)

Jika PHP sudah ditambahkan ke PATH sistem, jalankan perintah berikut di command line:

```bash
# Masuk ke direktori proyek
cd c:\laragon\www\monika

# Jalankan seeder data dummy standar
php spark db:seed DummyDataSeeder

# Atau jalankan seeder skala besar
php spark db:seed LargeScaleTestDataSeeder
```

### 2. Melalui Web Browser (Alternatif)

Jika command line tidak tersedia, Anda bisa menggunakan file runner yang telah disediakan:

1. Pastikan web server (misalnya Apache di Laragon) sudah berjalan
2. Akses file runner melalui browser:

Untuk data dummy standar:
```
http://localhost/monika/seed_runner.php
```

Untuk data dummy skala besar:
```
http://localhost/monika/large_seed_runner.php
```

**Catatan Penting:** File runner web hanya untuk keperluan development/testing. Jangan biarkan file ini terpapar di lingkungan produksi karena bisa membahayakan keamanan sistem.

### 3. Melalui Command Line dengan Jalur Lengkap (Jika PHP tidak di PATH)

Jika PHP tidak dikenali di command line, gunakan jalur lengkap ke executable PHP:

```bash
# Contoh untuk Laragon di Windows
"C:\laragon\bin\php\php-8.2.4-Win32-vs16-x64\php.exe" spark db:seed DummyDataSeeder
```

Sesuaikan jalur dengan versi PHP yang Anda gunakan.

## Isi Data Dummy

### Data Standar (DummyDataSeeder):
- **Roles**: 5 peran pengguna
- **Users**: 7 pengguna dengan berbagai peran
- **Master Kegiatan**: 5 kegiatan survei
- **Dokumen Survei**: 50 dokumen
- **Anomali Log**: 30 log anomali

### Data Skala Besar (LargeScaleTestDataSeeder):
- **Dokumen Survei**: 500 dokumen
- **Anomali Log**: 200 log anomali

## Struktur Seeder

File-file seeder yang dibuat:
- `app/Database/Seeds/RolesSeeder.php` - Data peran pengguna
- `app/Database/Seeds/UsersSeeder.php` - Data pengguna
- `app/Database/Seeds/MasterKegiatanSeeder.php` - Data kegiatan survei
- `app/Database/Seeds/DokumenSurveiSeeder.php` - Data dokumen survei
- `app/Database/Seeds/AnomaliLogSeeder.php` - Data log anomali
- `app/Database/Seeds/DummyDataSeeder.php` - Seeder utama
- `app/Database/Seeds/LargeScaleTestDataSeeder.php` - Seeder skala besar

## Catatan Keamanan

- Jangan jalankan seeder di lingkungan produksi karena akan mengganti data asli
- File runner web (`seed_runner.php` dan `large_seed_runner.php`) hanya untuk development
- Hapus file runner web setelah digunakan di lingkungan production

## Troubleshooting

Jika mengalami masalah saat menjalankan seeder:

1. Pastikan konfigurasi database di `.env` sudah benar
2. Pastikan database 'monika' sudah dibuat
3. Pastikan user database memiliki hak akses CREATE, INSERT, UPDATE, DELETE
4. Pastikan tidak ada constraint violation (urutan seeder harus dipertahankan)

## Dokumentasi Lengkap

Untuk dokumentasi lengkap tentang data dummy, lihat file: `DOKUMENTASI_DATA_DUMMY.md`