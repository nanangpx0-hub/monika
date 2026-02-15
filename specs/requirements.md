# **Product Requirements Document (PRD)**

**Nama Aplikasi:** MONIKA (Monitoring Nilai Kinerja & Anomali)

**Versi Dokumen:** 1.0

**Tanggal:** 27 Januari 2026

## **1\. Pendahuluan**

### **1.1 Tujuan Aplikasi**

MONIKA bertujuan untuk mendigitalisasi proses pemantauan kualitas data survei statistik. Sistem ini berfokus pada pelacakan anomali (error) pada dokumen survei dan mengevaluasi kinerja seluruh aktor yang terlibat (Petugas Lapangan, Pengolahan, dan Pengawas) secara objektif berbasis data.

### **1.2 Lingkup Masalah**

* Sulitnya melacak dokumen mana yang bermasalah dan siapa petugas yang bertanggung jawab.  
* Evaluasi petugas seringkali subjektif tanpa data kuantitatif (jumlah error/dokumen).  
* Data dari berbagai kegiatan survei (Susenas, Sakernas, dll) sering tercampur.

## **2\. Aktor & Hak Akses (User Classes)**

Sistem ini memiliki hierarki pengguna berbasis peran (*Role-Based Access Control*) sebagai berikut:

| Aktor | Deskripsi Peran | Hak Akses Utama |
| :---- | :---- | :---- |
| **Administrator** | Pengelola Sistem Utama | Manajemen User, Master Kegiatan, Konfigurasi Sistem, Akses Penuh Laporan. |
| **Petugas Pendataan (PCL)** | Mitra Lapangan | Melaporkan setoran dokumen (Upload/Input ID Dokumen), Melihat riwayat error miliknya. |
| **Petugas Pengolahan** | Mitra Entry/Editing | Melakukan entry data, **Melaporkan Anomali/Error** yang ditemukan pada dokumen. |
| **Pengawas Lapangan (PML)** | Supervisor PCL | Memantau progres & kualitas tim PCL di bawahnya. |
| **Pengawas Pengolahan** | Supervisor Entry | Memantau produktivitas & temuan error tim pengolahan. |

## **3\. Kebutuhan Fungsional (Functional Requirements)**

### **3.1 Modul Otentikasi & Registrasi**

* **FR-01:** Sistem harus memungkinkan pengguna login menggunakan Username/Password.  
* **FR-02:** Sistem harus membatasi menu berdasarkan Role ID pengguna.  
* **FR-03:** Sistem menyediakan form registrasi mandiri untuk Mitra Statistik dengan isian wajib: NIK (16 digit), Sobat ID, dan Pilihan Role (Pendataan/Pengolahan).

### **3.2 Modul Master Kegiatan (Survey Management)**

* **FR-04:** Admin dapat membuat Kegiatan Survei baru (contoh: "Sakernas Feb 2026") dengan atribut Tanggal Mulai dan Selesai.  
* **FR-05:** Admin dapat mengubah status kegiatan menjadi "Selesai" untuk mengarsipkan data.  
* **FR-06:** Setiap dokumen yang diinput ke sistem **WAJIB** dikaitkan dengan satu Kegiatan Aktif.

### **3.3 Modul Transaksi Dokumen (Flow Data)**

* **FR-07 (Setor):** Petugas Pendataan menginput data penyetoran dokumen (Kode Wilayah, Tanggal Setor) dan memilih Kegiatan terkait.  
* **FR-08 (Validasi):** Petugas Pengolahan dapat mengubah status dokumen dari Uploaded menjadi Sudah Entry.  
* **FR-09 (Flagging Error):** Petugas Pengolahan dapat melaporkan anomali pada dokumen tertentu. Sistem mewajibkan input: Jenis Error & Keterangan. Status dokumen otomatis berubah menjadi Error.  
* **FR-10 (Perbaikan):** Jika dokumen Error diperbaiki, status berubah menjadi Valid namun sistem tetap menyimpan *flag* pernah\_error \= 1 (True).

### **3.4 Modul Monitoring & Dashboard**

* **FR-11 (Filter):** Dashboard harus memiliki filter global berdasarkan "Master Kegiatan".  
* **FR-12 (Statistik):** Menampilkan widget real-time: Total Dokumen, Selesai Entry, Sedang Error, dan Valid Murni.  
* **FR-13 (Klasemen PCL):** Menampilkan daftar Petugas Pendataan diurutkan berdasarkan jumlah error terbanyak (untuk identifikasi petugas bermasalah).

### **3.5 Modul Evaluasi Kinerja (360 Degree)**

* **FR-14 (Evaluasi PCL):** Menghitung skor berdasarkan Rasio Error (Total Error / Total Dokumen).  
* **FR-15 (Evaluasi Pengolahan):** Menghitung skor berdasarkan Produktivitas (Jumlah Dokumen) dan Kejelian (Jumlah Temuan Error).  
* **FR-16 (Evaluasi Pengawas):** Menghitung skor Pengawas berdasarkan rata-rata kinerja Tim di bawahnya (Agregat).

## **4\. Kebutuhan Non-Fungsional (Non-Functional Requirements)**

* **NFR-01 (Security):** Password pengguna harus disimpan dalam bentuk Hash (Bcrypt/Argon2).  
* **NFR-02 (Usability):** Antarmuka pengguna harus responsif (Mobile-Friendly) menggunakan framework AdminLTE 3.2.  
* **NFR-03 (Performance):** Dashboard monitoring harus memuat data \< 3 detik untuk volume data hingga 10.000 dokumen per kegiatan.  
* **NFR-04 (Integrity):** Penghapusan Master Kegiatan harus memiliki opsi *Cascade* (menghapus dokumen terkait) atau *Restrict* (mencegah hapus jika ada data) untuk menjaga integritas database.

## **5\. Struktur Data Ringkas**

Sistem dibangun di atas database relasional (MySQL/MariaDB) dengan entitas utama:

1. users (Data Petugas, NIK, Sobat ID, Supervisor).  
2. roles (Hak akses).  
3. master\_kegiatan (Pemisah periode survei).  
4. dokumen\_survei (Data transaksi dokumen, Status, ID Kegiatan).  
5. anomali\_log (Detail error yang ditemukan).

## **6\. Kriteria Penerimaan (Acceptance Criteria)**

Fitur dinyatakan selesai jika:

1. Admin bisa membuat Kegiatan "Uji Coba 2026".  
2. Mitra bisa mendaftar dan login.  
3. Petugas Pengolahan bisa menandai satu dokumen sebagai "Error".  
4. Nama Petugas Pendataan pemilik dokumen error tersebut muncul di urutan teratas tabel "Ranking Anomali" di Dashboard.  
5. Data di dashboard berubah ketika filter Kegiatan diganti.