# **Design Document: Fitur Master Kegiatan (Aplikasi MONIKA)**

**Project:** MONIKA (Monitoring Nilai Kinerja & Anomali)

**Versi:** 1.1 (Updated with Branding)

**Tanggal:** 27 Januari 2026

**Status:** Ready for Implementation

## **1\. Pendahuluan**

### **1.1 Latar Belakang**

Aplikasi **MONIKA** saat ini menyimpan semua dokumen survei dalam satu tabel besar tanpa pemisahan periode. Padahal, instansi statistik biasanya menangani banyak kegiatan berbeda dalam satu tahun (contoh: *Sakernas Februari*, *Susenas Maret*, *Sensus Pertanian*, dll).

Tanpa pemisahan ini:

1. Evaluasi kinerja petugas menjadi bias (tercampur antar kegiatan).  
2. Sulit memfilter monitoring error untuk kegiatan yang spesifik sedang berjalan.  
3. Manajemen data menjadi tidak rapi seiring berjalannya waktu.

### **1.2 Tujuan**

1. **Segregasi Data:** Mengelompokkan dokumen, log error, dan capaian kinerja berdasarkan entitas "Kegiatan".  
2. **Manajemen Periode:** Admin dapat membuka (Aktif) atau menutup (Selesai) suatu kegiatan.  
3. **Evaluasi Spesifik:** Memungkinkan penilaian kinerja Mitra Statistik per proyek/kegiatan.

## **2\. Desain Database (Schema)**

Diperlukan penambahan satu tabel master dan modifikasi tabel transaksi dokumen.

### **2.1 Tabel Baru: master\_kegiatan**

Tabel ini berfungsi sebagai referensi utama (Parent) untuk semua aktivitas survei.

| Kolom | Tipe Data | Keterangan |
| :---- | :---- | :---- |
| id\_kegiatan | INT (PK, AI) | ID Unik Kegiatan |
| nama\_kegiatan | VARCHAR(100) | Contoh: "Survei Angkatan Kerja Nasional 2026" |
| kode\_kegiatan | VARCHAR(20) | Kode unik (Slug). Contoh: "SAKERNAS26" |
| tanggal\_mulai | DATE | Awal periode kegiatan |
| tanggal\_selesai | DATE | Akhir periode kegiatan |
| status | ENUM | 'Aktif', 'Selesai' (Default: Aktif) |
| created\_at | TIMESTAMP | Tanggal pembuatan record |

### **2.2 Modifikasi Tabel: dokumen\_survei**

Setiap dokumen survei yang diupload atau diolah wajib memiliki referensi ke id\_kegiatan.

* **Alter Table:** Tambah kolom id\_kegiatan (INT) sebagai Foreign Key.  
* **Constraint:** ON DELETE CASCADE (Jika kegiatan dihapus, dokumen terkait ikut terhapus untuk menjaga kebersihan data, atau RESTRICT jika ingin aman).

### **2.3 SQL Script (Implementation Ready)**

Copy-paste script ini ke phpMyAdmin untuk menerapkan perubahan:

\-- 1\. Buat Tabel Master Kegiatan  
CREATE TABLE \`master\_kegiatan\` (  
  \`id\_kegiatan\` int(11) NOT NULL AUTO\_INCREMENT,  
  \`nama\_kegiatan\` varchar(100) NOT NULL,  
  \`kode\_kegiatan\` varchar(20) NOT NULL,  
  \`tanggal\_mulai\` date NOT NULL,  
  \`tanggal\_selesai\` date NOT NULL,  
  \`status\` enum('Aktif','Selesai') NOT NULL DEFAULT 'Aktif',  
  \`created\_at\` timestamp NOT NULL DEFAULT current\_timestamp(),  
  PRIMARY KEY (\`id\_kegiatan\`),  
  UNIQUE KEY \`kode\_unik\` (\`kode\_kegiatan\`)  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

\-- 2\. Isi Dummy Data Awal  
INSERT INTO \`master\_kegiatan\` (\`nama\_kegiatan\`, \`kode\_kegiatan\`, \`tanggal\_mulai\`, \`tanggal\_selesai\`, \`status\`) VALUES  
('Sakernas Februari 2026', 'SAK26FEB', '2026-02-01', '2026-02-28', 'Aktif'),  
('Susenas Maret 2026', 'SSN26MAR', '2026-03-01', '2026-03-31', 'Aktif');

\-- 3\. Modifikasi Tabel Dokumen (Pastikan tabel dokumen\_survei kosong atau update data dulu)  
ALTER TABLE \`dokumen\_survei\`  
ADD COLUMN \`id\_kegiatan\` int(11) NULL AFTER \`id\_dokumen\`;

\-- (Safety Step: Set default ke ID 1 untuk data lama jika ada)  
UPDATE \`dokumen\_survei\` SET \`id\_kegiatan\` \= 1 WHERE \`id\_kegiatan\` IS NULL;

\-- Tambahkan Foreign Key  
ALTER TABLE \`dokumen\_survei\`  
ADD CONSTRAINT \`fk\_dok\_kegiatan\` FOREIGN KEY (\`id\_kegiatan\`) REFERENCES \`master\_kegiatan\` (\`id\_kegiatan\`) ON DELETE CASCADE ON UPDATE CASCADE;

## **3\. Arsitektur Aplikasi (CodeIgniter 4\)**

### **3.1 Model (app/Models/KegiatanModel.php)**

Model ini menangani CRUD untuk tabel master kegiatan.

\<?php  
namespace App\\Models;  
use CodeIgniter\\Model;

class KegiatanModel extends Model  
{  
    protected $table \= 'master\_kegiatan';  
    protected $primaryKey \= 'id\_kegiatan';  
    protected $allowedFields \= \['nama\_kegiatan', 'kode\_kegiatan', 'tanggal\_mulai', 'tanggal\_selesai', 'status'\];

    // Mengambil list kegiatan yang masih berjalan untuk dropdown form  
    public function getKegiatanAktif()  
    {  
        return $this-\>where('status', 'Aktif')-\>findAll();  
    }  
}

### **3.2 Update Model Monitoring (DokumenModel.php)**

Logic monitoring harus diperbarui agar menerima parameter id\_kegiatan. Ini memungkinkan dashboard menampilkan statistik spesifik per kegiatan.

// Update pada method getRekapKinerja  
public function getRekapKinerja($id\_kegiatan \= null)  
{  
    $builder \= $this-\>db-\>table('users');  
    $builder-\>select('users.fullname, users.sobat\_id,   
                      COUNT(DISTINCT dokumen\_survei.id\_dokumen) as total\_dokumen,  
                      COUNT(DISTINCT anomali\_log.id\_anomali) as total\_error');  
      
    // Join tabel  
    $builder-\>join('dokumen\_survei', 'users.id\_user \= dokumen\_survei.id\_petugas\_pendataan', 'left');  
    $builder-\>join('anomali\_log', 'dokumen\_survei.id\_dokumen \= anomali\_log.id\_dokumen', 'left');  
      
    // Logic Filter Kegiatan  
    if ($id\_kegiatan \!= null) {  
        $builder-\>where('dokumen\_survei.id\_kegiatan', $id\_kegiatan);  
    }

    $builder-\>where('users.id\_role', 3); // Role Mitra Lapangan  
    $builder-\>groupBy('users.id\_user');  
    $builder-\>orderBy('total\_error', 'DESC');  
      
    return $builder-\>get()-\>getResultArray();  
}

## **4\. Desain Antarmuka (UI/UX)**

### **4.1 Modul Master Kegiatan (Admin Only)**

* **Lokasi:** Sidebar \> Menu "Master Data" \> "Kegiatan Survei".  
* **Tampilan:** Tabel DataTables.  
* **Aksi:** Tambah, Edit, Hapus, Tutup Kegiatan (Ubah status ke 'Selesai').

### **4.2 Dashboard Monitoring (Evaluasi)**

* **Penambahan:** Dropdown Filter di pojok kanan atas Dashboard.  
  * Label: "Filter Kegiatan:"  
  * Opsi: \[Semua Kegiatan\] \+ List Kegiatan Aktif.  
* **Interaksi:** Saat user memilih kegiatan tertentu, seluruh widget (Total Dokumen, Total Error) dan Tabel Klasemen Petugas akan me-refresh data sesuai kegiatan tersebut.

### **4.3 Form Input Dokumen (Petugas)**

* **Penambahan:** Field Select Kegiatan (Wajib Diisi).  
* **Validasi:** Dokumen tidak bisa diupload jika tidak memilih kegiatan yang valid/aktif.

## **5\. Rencana Implementasi (Action Plan)**

1. **Database Migration:** Eksekusi SQL Script pada poin 2.3.  
2. **Backend Development:**  
   * Buat KegiatanModel.php.  
   * Buat KegiatanController.php (CRUD).  
   * Update MonitoringController.php agar menangkap input filter id\_kegiatan.  
3. **Frontend Development:**  
   * Buat View kegiatan/index.php dan kegiatan/form.php.  
   * Update View monitoring/index.php dengan menambahkan dropdown filter.  
4. **Testing:**  
   * Coba input dokumen ke "Sakernas".  
   * Coba input dokumen ke "Susenas".  
   * Cek Dashboard: pastikan data Sakernas dan Susenas bisa dilihat terpisah.