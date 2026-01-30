# DOKUMENTASI TEKNIS APLIKASI MONIKA

**(Monitoring Nilai Kinerja & Anomali)**

---

## 1. PENDAHULUAN

### 1.1 Deskripsi Sistem

MONIKA adalah sistem aplikasi berbasis web yang dirancang untuk memantau kinerja petugas lapangan (PCL) dan kualitas data melalui pelaporan dokumen survei serta pencatatan anomali (error) pada tahap pengolahan data. Sistem ini memfasilitasi alur kerja dari kegiatan survei, pengumpulan dokumen, entry data, hingga pelaporan error.

### 1.2 Teknologi Stack

Aplikasi ini dibangun menggunakan arsitektur MVC (Model-View-Controller) dengan teknologi berikut:

**Backend:**

- **Language:** PHP 8.1+
- **Framework:** CodeIgniter 4.x
- **Database:** MySQL / MariaDB (Driver: MySQLi/PDO)

**Frontend:**

- **Template Engine:** PHP Native Views (CodeIgniter View)
- **CSS Framework:** Bootstrap 4.6 (via AdminLTE 3.2)
- **JS Libraries:** jQuery 3.6, FontAwesome 5.15

---

## 2. ANALISIS KODE FRONTEND

### 2.1 Struktur Tampilan (Views)

File tampilan berlokasi di `app/Views/` dengan struktur modular:

- `layout/`: Template utama (`template.php`) yang memuat header, sidebar, dan footer.
- `auth/`: Halaman Login dan Register.
- `dashboard/`: Halaman utama statistik.
- `dokumen/`: Manajemen dokumen survei (List, Create).
- `kegiatan/`: Manajemen master kegiatan.
- `laporan/`: Halaman laporan kinerja.
- `monitoring/`: Halaman monitoring khusus.

### 2.2 Integrasi UI/UX

- **Base Theme:** AdminLTE 3.2 (LTE Admin Template).
- **Responsive Design:** Mendukung tampilan mobile via Bootstrap Grid.
- **Micro-interactions:** Menggunakan jQuery untuk handling form submit, modal, dan notifikasi (SweetAlert toast atau FlashData session).

---

## 3. ANALISIS KODE BACKEND

### 3.1 Arsitektur

Sistem menggunakan pola MVC standar CodeIgniter 4:

- **Controllers (`app/Controllers`):** Mengatur flow logika dan input user.
- **Models (`app/Models`):** Berinteraksi langsung dengan database.
- **Filters (`app/Config/Filters.php`):** Middleware untuk otentikasi (Cek session login).

### 3.2 Controller Utama

| Controller | Deskripsi | Akses Role |
| :--- | :--- | :--- |
| `Auth` | Menangani Login, Register, Logout. | Public |
| `Home` | Menangani Dashboard utama. | Authenticated Users |
| `Kegiatan` | CRUD data Master Kegiatan Survei. | Admin (1) |
| `Dokumen` | Upload dokumen, update status entry, lapor error. | Admin (1), PCL (3), Pengolah (4) |
| `Monitoring`| Menampilkan rekapitulasi monitoring global. | Authenticated Users |

### 3.3 Routing (`app/Config/Routes.php`)

Routing dikelompokkan berdasarkan modul dan dilindungi oleh filter `auth`:

- `/kegiatan/*` -> Akses manajemen kegiatan.
- `/dokumen/*` -> Akses manajemen dokumen.
- `/laporan/*` -> Akses laporan kinerja.

---

## 4. ANALISIS DATABASE

### 4.1 Skema Database (`monika`)

Database terdiri dari 5 tabel utama yang saling berelasi.

#### ERD Sederhana

`roles` (1) <--- (M) `users` (1) <--- (M) `dokumen_survei` (1) <--- (M) `anomali_log`
`master_kegiatan` (1) <--- (M) `dokumen_survei`

### 4.2 Detail Tabel

**1. `users`**
Menyimpan data pengguna aplikasi.

- PK: `id_user`
- FK: `id_role` -> `roles.id_role`
- FK: `id_supervisor` -> `users.id_user` (Self-referencing untuk struktur tim)

**2. `roles`**
Menyimpan definisi hak akses.

- 1: Administrator
- 3: Petugas Pendataan (PCL)
- 4: Petugas Pengolahan
- 5: Pengawas Lapangan (PML)
- 6: Pengawas Pengolahan

**3. `master_kegiatan`**
Menyimpan daftar survei/sensus yang berjalan.

- PK: `id_kegiatan`
- Kolom Penting: `kode_kegiatan`, `tanggal_mulai`, `tanggal_selesai`, `status`.

**4. `dokumen_survei`**
Tabel inti yang mencatat pergerakan dokumen.

- `status`: ENUM('Uploaded', 'Sudah Entry', 'Error', 'Valid')
- `pernah_error`: Flag boolean (0/1) untuk menandai jika dokumen pernah dikembalikan/error.
- FK: `id_petugas_pendataan` (PCL penanggung jawab).
- FK: `processed_by` (Petugas entry/editor terakhir).

**5. `anomali_log`**
Mencatat riwayat error pada dokumen.

- FK: `id_dokumen`
- FK: `id_petugas_pengolahan` (Pelapor error).
- Kolom: `jenis_error`, `keterangan`.

---

## 5. ANALISIS LOGIKA PROSES BISNIS

### 5.1 Alur Kerja Dokumen (Document Lifecycle)

1. **Penyerahan (Upload/Setor):**
    - **Aktor:** Petugas PCL atau Admin.
    - **Aksi:** Menginput data penyerahan dokumen (Kode Wilayah, Kegiatan).
    - **Status Awal:** `Uploaded`.

2. **Proses Pengolahan (Entry/Editing):**
    - **Aktor:** Petugas Pengolahan.
    - **Aksi:** Memilih dokumen yang statusnya `Uploaded`.
    - **Keputusan:**
        - **Jika Data Valid:** Petugas menandai "Sudah Entry". Status berubah menjadi `Sudah Entry`.
        - **Jika Data Anomali:** Petugas melaporkan error via fitur "Lapor Error".

3. **Pelaporan Anomali:**
    - **Event:** Petugas Pengolahan menemukan isian tidak wajar.
    - **Sistem:**
        - Mencatat ke tabel `anomali_log`.
        - Update status dokumen menjadi `Error`.
        - Set flag `pernah_error` = 1.

### 5.2 Manajemen Role dan Izin

* **Administrator:** Akses penuh (Kegiatan, User, Dokumen, Laporan).
- **PCL (Pendataan):** Fokus pada setoran dokumen (`dokumen/create`).
- **Pengolahan:** Fokus pada update status dokumen dan pelaporan error.

---

## 6. ANALISIS STRUKTUR FILE

```
c:/laragon/www/monika/
├── app/
│   ├── Config/       # Konfigurasi (Database, Routes, App)
│   ├── Controllers/  # Logika Bisnis (Auth, Dokumen, Kegiatan)
│   ├── Models/       # Interaksi Database
│   └── Views/        # Tampilan HTML (layout, dashboard, dokumen)
├── public/           # Aset publik (CSS, JS, Images)
├── writables/        # Cache, Logs, Session files
├── .env              # Environment Variables
├── spark             # CLI Tools CodeIgniter
└── composer.json     # Dependency Management
```

---

## 7. SARAN PENGEMBANGAN

Untuk pengembangan selanjutnya (Sprint berikutnya), disarankan:

1. **API Integration:** Membuat REST API untuk integrasi dengan aplikasi mobile PCL.
2. **Automated Testing:** Menambahkan Unit Test (`phpunit`) untuk memastikan logika status dokumen tidak regresi.
3. **Advanced Reporting:** Menambahkan fitur export Excel/PDF untuk laporan bulanan.
