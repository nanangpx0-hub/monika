# Dokumentasi Data Dummy Sistem Monika

## Tujuan Data Dummy

Data dummy dalam sistem Monika bertujuan untuk:

1. **Pengujian Fungsionalitas**: Menyediakan data uji untuk menguji seluruh fitur sistem sebelum deployment
2. **Pengembangan Aplikasi**: Mempermudah proses pengembangan dengan menyediakan data contoh
3. **Demonstrasi Sistem**: Menyediakan data contoh untuk presentasi dan demo sistem
4. **Pengujian Performa**: Menggunakan data dalam jumlah besar untuk menguji performa sistem
5. **Validasi Integrasi**: Memastikan semua modul sistem berfungsi dengan baik secara keseluruhan

## Struktur Data Dummy

### 1. Tabel `roles`

Tabel ini menyimpan data peran pengguna dalam sistem:

- **Jumlah Record**: 5 record
- **Struktur**:
  - `id_role`: ID unik untuk setiap peran (1, 3, 4, 5, 6)
  - `role_name`: Nama peran (Administrator, Petugas Pendataan, dll.)
  - `description`: Deskripsi singkat tentang fungsi peran

### 2. Tabel `users`

Tabel ini menyimpan data pengguna sistem:

- **Jumlah Record**: 7 record (termasuk admin default)
- **Struktur**:
  - `id_user`: ID unik pengguna (auto increment)
  - `fullname`: Nama lengkap pengguna
  - `username`: Username unik untuk login
  - `email`: Alamat email unik
  - `password`: Password terenkripsi (menggunakan bcrypt)
  - `nik_ktp`: Nomor identitas (opsional)
  - `sobat_id`: ID partner (opsional)
  - `id_role`: Referensi ke tabel roles
  - `id_supervisor`: Referensi ke pengguna lain sebagai supervisor (opsional)
  - `is_active`: Status aktif/non-aktif pengguna
  - `created_at`, `updated_at`: Timestamp pembuatan dan pembaruan

### 3. Tabel `master_kegiatan`

Tabel ini menyimpan data kegiatan survei:

- **Jumlah Record**: 5 record
- **Struktur**:
  - `id_kegiatan`: ID unik kegiatan (auto increment)
  - `nama_kegiatan`: Nama lengkap kegiatan survei
  - `kode_kegiatan`: Kode unik untuk kegiatan
  - `tanggal_mulai`, `tanggal_selesai`: Rentang waktu kegiatan
  - `status`: Status kegiatan (Aktif/Selesai)

### 4. Tabel `dokumen_survei`

Tabel ini menyimpan data dokumen hasil survei:

- **Jumlah Record**: 50 record
- **Struktur**:
  - `id_dokumen`: ID unik dokumen (auto increment)
  - `id_kegiatan`: Referensi ke tabel master_kegiatan
  - `kode_wilayah`: Kode wilayah survei
  - `id_petugas_pendataan`: Referensi ke petugas pendataan
  - `processed_by`: Referensi ke petugas pengolahan
  - `status`: Status dokumen (Uploaded, Sudah Entry, Error, Valid)
  - `pernah_error`: Indikator apakah pernah ada error
  - `tanggal_setor`: Tanggal dokumen disetorkan
  - `created_at`, `updated_at`: Timestamp pembuatan dan pembaruan

### 5. Tabel `anomali_log`

Tabel ini menyimpan log anomali atau error dalam data survei:

- **Jumlah Record**: 30 record
- **Struktur**:
  - `id_anomali`: ID unik anomali (auto increment)
  - `id_dokumen`: Referensi ke tabel dokumen_survei
  - `id_petugas_pengolahan`: Referensi ke petugas yang menemukan anomali
  - `jenis_error`: Jenis error yang ditemukan
  - `keterangan`: Detail penjelasan tentang anomali
  - `created_at`: Timestamp pencatatan anomali

## Skema Seeder

### File-file Seeder

1. **RolesSeeder.php**: Mengisi data peran pengguna
2. **UsersSeeder.php**: Mengisi data pengguna dengan berbagai peran
3. **MasterKegiatanSeeder.php**: Mengisi data kegiatan survei
4. **DokumenSurveiSeeder.php**: Mengisi data dokumen survei (50 record) dengan relasi valid
5. **AnomaliLogSeeder.php**: Mengisi data log anomali yang terhubung ke dokumen yang valid
6. **LargeScaleTestDataSeeder.php**: Seeder khusus untuk generating data besar (500 dokumen, 200 anomali)
7. **DummyDataSeeder.php**: Seeder utama yang menjalankan seeder dasar (nomor 1-5) dalam urutan yang benar

## Cara Menjalankan Seeder

Untuk menjalankan seeder data dummy, gunakan perintah berikut di terminal:

```bash
php spark db:seed DummyDataSeeder
```

### Untuk Pengujian Skala Besar / Stress Test

Jika Anda membutuhkan data dalam jumlah yang lebih besar (500++ dokumen), jalankan perintah ini:
> **Peringatan**: Perintah ini akan `TRUNCATE` (menghapus) semua data dummy dokumen dan anomali yang ada sebelumnya.

```bash
php spark db:seed LargeScaleTestDataSeeder
```

### Seeder Individual

```bash
php spark db:seed RolesSeeder
php spark db:seed MasterKegiatanSeeder
php spark db:seed UsersSeeder
php spark db:seed DokumenSurveiSeeder
php spark db:seed AnomaliLogSeeder
```

## Catatan Penting

1. **Urutan Eksekusi**: Seeder harus dijalankan dalam urutan tertentu karena adanya relasi foreign key
2. **Data Produksi**: Jangan jalankan seeder ini di lingkungan produksi karena akan menghapus data yang ada
3. **Konsistensi Data**: Data dummy dirancang untuk mencerminkan skenario nyata dalam sistem Monika
4. **Pengujian Komprehensif**: Jumlah data dummy cukup besar untuk pengujian performa dan fungsionalitas

## Contoh Data

Berikut beberapa contoh data yang akan dihasilkan:

- **Pengguna**: Berbagai jenis pengguna dengan peran berbeda (Administrator, Petugas Pendataan, Petugas Pengolahan, Pengawas)
- **Kegiatan**: Berbagai jenis kegiatan survei statistik seperti Sakernas, Susenas, RSE
- **Dokumen**: Banyak dokumen survei dari berbagai wilayah dan kegiatan
- **Anomali**: Berbagai jenis error yang mungkin terjadi dalam proses survei

Dokumentasi ini akan membantu pengembang dan tester memahami struktur serta tujuan dari data dummy dalam sistem Monika.
