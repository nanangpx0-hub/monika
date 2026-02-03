# Laporan Implementasi Teknis Sistem MONIKA
**Versi 1.0**

**Tanggal Implementasi:** 31 Januari 2026  
**Developer:** Nanangpx  
**Aplikasi:** MONIKA (MOnitoring NIlai Kinerja & Anomali)

---

## 1. Ringkasan Eksekutif

Dokumen ini merinci serangkaian pembaruan sistem yang dilakukan untuk meningkatkan kapabilitas manajemen pengguna, pengawasan lapangan (PML), pengumpulan data (PCL), serta keamanan dan auditabilitas sistem. Perubahan mencakup penambahan struktur database baru, modul fitur baru, perbaikan validasi, dan peningkatan antarmuka pengguna (UI).

## 2. Daftar Perubahan & Implementasi

### A. Modul Manajemen User (User Management)

#### 1. Reset Password & Toggle Status
*   **Alasan Teknis:** Administrator memerlukan cara cepat untuk memulihkan akses pengguna yang lupa password tanpa harus menghapus akun, serta menonaktifkan akun sementara (suspend) untuk keamanan.
*   **Implementasi:**
    *   Menambahkan method `resetPassword`, `resetPasswordForm`, dan `toggleStatus` pada controller `Admin\Users`.
    *   **Config:** Route baru `/admin/users/reset-password/{id}` dan `/admin/users/toggle-status/{id}`.
*   **Dampak:** Meningkatkan efisiensi admin dalam me-manage lifecycle akun pengguna.
*   **Perbandingan:**
    *   *Sebelum:* Admin harus edit manual database atau tidak ada fitur reset.
    *   *Sesudah:* Form UI khusus untuk reset password (manual input atau auto-generate) dan tombol toggle aktif/non-aktif.

#### 2. Perbaikan Sintaks Validasi (Bug Fix)
*   **Alasan Teknis:** Ditemukan error `ValidationException` karena penggunaan sintaks legacy CodeIgniter 3 `min_length(x)` yang tidak didukung penuh atau deprecated, diganti dengan standar array syntax CodeIgniter 4 `min_length[x]`.
*   **Implementasi:** Refactoring rules pada `Users::store`, `Users::update`, `Pml::store`, `Pml::update`, `Auth::register`, dan `Api\Users`.
*   **Kode Snippet:**
    ```php
    // Sebelum
    'password' => 'required|min_length(6)'
    // Sesudah
    'password' => 'required|min_length[6]'
    ```

### B. Modul PCL (Petugas Pendataan Lapangan)

#### 1. Import Data CSV
*   **Alasan Teknis:** Pendaftaran massal petugas (bulk insert) seringkali gagal atau lambat jika dilakukan manual satu per satu.
*   **Implementasi:**
    *   Membuat Library custom `App\Libraries\CsvImporter` untuk parsing dan validasi file CSV.
    *   UI baru di `views/admin/pcl/import.php` dengan template download.
*   **Dampak:** Mempercepat onboarding ratusan petugas PCL dalam satu waktu.

### C. Modul PML (Petugas Monitoring Lapangan)

#### 1. Laporan Kinerja & Activity Log
*   **Alasan Teknis:** Kebutuhan untuk memantau aktivitas pengawasan PML secara real-time dan berbasis lokasi.
*   **Implementasi:**
    *   Tabel database baru `pml_activities` untuk menyimpan log aktivitas + koordinat GPS.
    *   Model baru `PmlActivityModel` dengan method statistik (`getMonthlyStats`, dll).
    *   Dashboard view `views/admin/pml/performance.php` menggunakan Chart.js.

### D. Modul Dokumen & Survei

#### 1. Penambahan Kolom PML (Supervisor)
*   **Alasan Teknis:** Dokumen survei perlu dilacak tidak hanya berdasarkan PCL (pengumpul), tapi juga PML (pengawas) yang bertanggung jawab atas PCL tersebut.
*   **Implementasi:**
    *   Update Query di `DokumenModel::getDokumenWithRelations` untuk melakukan *Self-Join* ke tabel `users` (sebagai supervisor).
    *   Update View `dokumen/index.php` untuk menampilkan kolom "PML".
*   **Dampak:** Transparansi hierarki pengawasan dokumen lebih jelas.

#### 2. Fitur Administratif (Edit, Reset, Delete)
*   **Alasan Teknis:** Kesalahan entri status dokumen sering terjadi dan admin membutuhkan "Kill Switch" atau "Reset Button" tanpa melalui database.
*   **Implementasi:**
    *   **Edit:** Admin (Role 1) sekarang bisa mengedit dokumen dalam status apapun.
    *   **Reset Status:** Fitur baru untuk mengembalikan status dokumen ke "Setor" (kosongkan status proses & error).
    *   **Delete:** Hapus permanen dokumen dan log anomali terkait.
*   **Konfigurasi Baru:** Route `POST /dokumen/reset-status/(:num)` dan `POST /dokumen/delete/(:num)`.

### E. Audit Trail & Keamanan

#### 1. Enhanced Audit System
*   **Alasan Teknis:** Kebutuhan compliance untuk melacak siapa melakukan apa, kapan, dan dari IP mana.
*   **Implementasi:**
    *   Tabel baru `audit_logs`.
    *   Pencatatan otomatis pada aksi sensitif (Login, Crud Admin, Reset Password, Hapus Dokumen).
    *   Fitur Export Audit Log ke CSV.

### F. API Documentation
*   **Alasan Teknis:** Standarisasi endpoint untuk pengembangan frontend mobile/pihak ketiga.
*   **Implementasi:** Integrasi Swagger UI di `/api/docs` dengan spesifikasi OpenAPI 3.0 (`swagger.yaml`).

---

## 3. Perubahan Database (Schema Changes)

Berikut struktur tabel baru yang ditambahkan:

**1. Tabel `audit_logs`**
```sql
CREATE TABLE audit_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  action VARCHAR(255) NOT NULL,
  details TEXT NULL,
  ip_address VARCHAR(45) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**2. Tabel `pml_activities`**
```sql
CREATE TABLE pml_activities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_pml INT NOT NULL,
  activity_type VARCHAR(100) NOT NULL, -- e.g., 'Monitoring', 'Briefing'
  description TEXT NULL,
  location_lat DECIMAL(10,6),
  location_long DECIMAL(10,6),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**3. Tabel `users` (Alter)**
*   Added: `deleted_at` (DATETIME) - Untuk Soft Delete
*   Added: `last_login_at` (DATETIME)
*   Added: `password_changed_at` (DATETIME)

---

## 4. Langkah Validasi & Testing

Semua fitur telah divalidasi dengan skenario berikut:
1.  **Impor PCL:** Upload file CSV valid (sukses) dan invalid format (ditolak dengan pesan error).
2.  **Reset Password:** Generate password baru, login dengan password baru sukses.
3.  **Dokumen Workflow:**
    *   PCL setor dokumen -> Status "Setor".
    *   Admin Reset -> Status kembali kosong/awal.
    *   Admin Hapus -> Data hilang dari list & tersimpan di Audit Log.
4.  **API:** Akses `/api/docs` merender Swagger UI dengan benar.

## 5. Kesimpulan & Rekomendasi

Sistem MONIKA kini memiliki fondasi manajemen user dan pengawasan yang jauh lebih robust. Penambahan fitur audit trail dan reset status memberikan kontrol penuh kepada administrator untuk menangani anomali data operasional sehari-hari.

Disarankan untuk melakukan backup database secara berkala sebelum melakukan operasi "Hard Delete" pada dokumen, meskipun fitur Audit Trail sudah mencatat metadata penghapusan tersebut.
