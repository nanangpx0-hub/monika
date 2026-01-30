# User Manual - Sistem Manajemen Monika

## 1. Pendahuluan
Sistem ini dirancang untuk memantau kinerja petugas lapangan (PCL dan PML) serta mengelola dokumen survei.

## 2. Modul Manajemen PCL
Akses: Menu **Manajemen PCL**
Fungsi ini digunakan untuk mengelola data Petugas Pendataan Lapangan.

### Fitur Utama:
1.  **Daftar PCL**: Menampilkan tabel seluruh PCL terdaftar.
    -   **Filter**: Dapat memfilter berdasarkan Wilayah Kerja dan Status (Aktif/Non-Aktif).
    -   **Ekspor**: Klik tombol "Ekspor CSV" untuk mengunduh data PCL.
2.  **Tambah PCL**:
    -   Klik tombol "Tambah PCL".
    -   Isi form: Nama, Username, Email, Password, NIK, No Telp, Wilayah Kerja, Masa Tugas.
    -   Status default adalah "Aktif".
3.  **Edit PCL**:
    -   Klik tombol Edit (ikon pensil) pada baris PCL.
    -   Ubah data yang diperlukan. Kosongkan password jika tidak ingin mengubahnya.

## 3. Modul Manajemen PML
Akses: Menu **Manajemen PML**
Fungsi ini digunakan untuk mengelola data Petugas Monitoring Lapangan (Supervisor).

### Fitur Utama:
1.  **Daftar PML**: Menampilkan tabel PML.
2.  **Tambah/Edit PML**:
    -   Form serupa dengan PCL, namun dengan tambahan field **Wilayah Supervisi** dan **Kualifikasi**.
3.  **Penugasan PCL (Mapping)**:
    -   Pada halaman **Edit PML**, terdapat bagian "Daftar PCL Binaan".
    -   Anda dapat menambahkan PCL yang belum memiliki supervisor ke PML ini.
    -   Anda dapat melepas PCL dari binaan PML ini.
4.  **Monitoring Aktivitas**:
    -   Klik tombol "Log" (ikon mata) untuk melihat riwayat aktivitas PML (lokasi, waktu, deskripsi kegiatan).

## 4. Modul Manajemen User Sistem
Akses: Menu **Manajemen User**
Digunakan untuk mengelola pengguna umum (Admin, Pengolahan, dll).

-   Mendukung penambahan user dengan role: Administrator, Pengolahan, Pengawas Pengolahan.
-   Fitur Audit Log mencatat setiap perubahan user.

## 5. Dokumen Survei
-   **Status Dokumen**:
    -   **Setor**: Dokumen baru diupload/dikirim ke pengolahan.
    -   **Valid**: Dokumen telah diverifikasi dan valid.
    -   **Error**: Dokumen memiliki kesalahan.
-   **Pembersihan Data**:
    -   Gunakan tombol "Clean" untuk membersihkan karakter aneh pada data dokumen.

---
**Catatan Teknis:**
-   Password disimpan dengan enkripsi BCRYPT.
-   Sistem mencatat audit log untuk setiap aksi sensitif.
