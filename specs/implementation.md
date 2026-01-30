# **Implementation Plan: Master Kegiatan Feature**

**Project:** MONIKA (Monitoring Nilai Kinerja & Anomali)

**Modul:** Manajemen Kegiatan Survei

**Framework:** CodeIgniter 4 \+ AdminLTE 3.2

## **1\. Persiapan (Prerequisites)**

Sebelum memulai, pastikan:

1. Backup database db\_penelitian\_sistem yang ada saat ini.  
2. Pastikan service Apache dan MySQL berjalan.  
3. Pastikan tidak ada user yang sedang menginput data (Maintenance Mode).

## **2\. Tahap 1: Database Migration**

Eksekusi perintah SQL berikut di phpMyAdmin atau tool database client lainnya.

### **Langkah 1.1: Membuat Tabel Master**

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

### **Langkah 1.2: Seeding Data Awal (Wajib)**

Penting agar data lama tidak error saat relasi dibuat.

INSERT INTO \`master\_kegiatan\` (\`id\_kegiatan\`, \`nama\_kegiatan\`, \`kode\_kegiatan\`, \`tanggal\_mulai\`, \`tanggal\_selesai\`, \`status\`) VALUES  
(1, 'Kegiatan Umum / Legacy Data', 'LEGACY', '2025-01-01', '2025-12-31', 'Selesai'),  
(2, 'Sakernas Februari 2026', 'SAK26FEB', '2026-02-01', '2026-02-28', 'Aktif');

### **Langkah 1.3: Modifikasi Tabel Dokumen**

\-- Tambah kolom FK  
ALTER TABLE \`dokumen\_survei\`  
ADD COLUMN \`id\_kegiatan\` int(11) NULL AFTER \`id\_dokumen\`;

\-- Update data lama agar memiliki parent ID 1 (Legacy)  
UPDATE \`dokumen\_survei\` SET \`id\_kegiatan\` \= 1 WHERE \`id\_kegiatan\` IS NULL;

\-- Terapkan Foreign Key Constraint  
ALTER TABLE \`dokumen\_survei\`  
ADD CONSTRAINT \`fk\_dok\_kegiatan\` FOREIGN KEY (\`id\_kegiatan\`) REFERENCES \`master\_kegiatan\` (\`id\_kegiatan\`) ON DELETE CASCADE ON UPDATE CASCADE;

## **3\. Tahap 2: Backend Development (CI4)**

### **Langkah 2.1: Membuat Model (app/Models/KegiatanModel.php)**

\<?php

namespace App\\Models;

use CodeIgniter\\Model;

class KegiatanModel extends Model  
{  
    protected $table            \= 'master\_kegiatan';  
    protected $primaryKey       \= 'id\_kegiatan';  
    protected $allowedFields    \= \['nama\_kegiatan', 'kode\_kegiatan', 'tanggal\_mulai', 'tanggal\_selesai', 'status'\];

    // Helper untuk dropdown  
    public function getAktif()  
    {  
        return $this-\>where('status', 'Aktif')-\>findAll();  
    }  
}

### **Langkah 2.2: Update Model Dokumen (app/Models/DokumenModel.php)**

Modifikasi method getRekapKinerja agar menerima parameter filter.

    // ... kode sebelumnya ...

    public function getRekapKinerja($id\_kegiatan \= null)  
    {  
        $builder \= $this-\>db-\>table('users');  
        $builder-\>select('users.fullname, users.sobat\_id,   
                          COUNT(DISTINCT dokumen\_survei.id\_dokumen) as total\_dokumen,  
                          COUNT(DISTINCT anomali\_log.id\_anomali) as total\_error');  
          
        $builder-\>join('dokumen\_survei', 'users.id\_user \= dokumen\_survei.id\_petugas\_pendataan', 'left');  
        $builder-\>join('anomali\_log', 'dokumen\_survei.id\_dokumen \= anomali\_log.id\_dokumen', 'left');  
          
        // \--- LOGIC FILTER BARU \---  
        if ($id\_kegiatan && $id\_kegiatan \!= 'all') {  
            $builder-\>where('dokumen\_survei.id\_kegiatan', $id\_kegiatan);  
        }  
        // \-------------------------

        $builder-\>where('users.id\_role', 3); // Role Mitra Lapangan  
        $builder-\>groupBy('users.id\_user');  
        $builder-\>orderBy('total\_error', 'DESC');  
          
        return $builder-\>get()-\>getResultArray();  
    }  
      
    // Lakukan hal yang sama untuk getStatistikProgress() dan getKinerjaPengawas() jika perlu

### **Langkah 2.3: Membuat Controller Kegiatan (app/Controllers/Kegiatan.php)**

\<?php

namespace App\\Controllers;

use App\\Models\\KegiatanModel;

class Kegiatan extends BaseController  
{  
    protected $kegiatanModel;

    public function \_\_construct()  
    {  
        $this-\>kegiatanModel \= new KegiatanModel();  
    }

    public function index()  
    {  
        $data \= \[  
            'title' \=\> 'Master Kegiatan Survei',  
            'kegiatan' \=\> $this-\>kegiatanModel-\>findAll()  
        \];  
        return view('kegiatan/index', $data);  
    }

    public function store()  
    {  
        // Tambahkan validasi di sini jika perlu  
        $this-\>kegiatanModel-\>save(\[  
            'nama\_kegiatan' \=\> $this-\>request-\>getVar('nama'),  
            'kode\_kegiatan' \=\> strtoupper($this-\>request-\>getVar('kode')),  
            'tanggal\_mulai' \=\> $this-\>request-\>getVar('tgl\_mulai'),  
            'tanggal\_selesai' \=\> $this-\>request-\>getVar('tgl\_selesai'),  
            'status' \=\> 'Aktif'  
        \]);  
        return redirect()-\>to('/kegiatan')-\>with('msg', 'Kegiatan berhasil dibuat.');  
    }

    public function updateStatus($id)  
    {  
        $statusBaru \= $this-\>request-\>getVar('status'); // 'Aktif' atau 'Selesai'  
        $this-\>kegiatanModel-\>update($id, \['status' \=\> $statusBaru\]);  
        return redirect()-\>to('/kegiatan')-\>with('msg', 'Status kegiatan diperbarui.');  
    }  
      
    public function delete($id)  
    {  
        $this-\>kegiatanModel-\>delete($id);  
        return redirect()-\>to('/kegiatan')-\>with('msg', 'Kegiatan dihapus.');  
    }  
}

### **Langkah 2.4: Update Controller Monitoring (app/Controllers/Monitoring.php)**

    // ... use models ...  
      
    public function index()  
    {  
        $dokumenModel \= new DokumenModel();  
        $kegiatanModel \= new \\App\\Models\\KegiatanModel();

        // Ambil filter dari URL ?kegiatan=2  
        $filter\_kegiatan \= $this-\>request-\>getGet('kegiatan');

        $data \= \[  
            'title' \=\> 'Monitoring & Evaluasi',  
            'list\_kegiatan' \=\> $kegiatanModel-\>getAktif(), // Untuk Dropdown  
            'selected\_kegiatan' \=\> $filter\_kegiatan,  
              
            // Kirim parameter filter ke Model  
            'kinerja\_lapangan' \=\> $dokumenModel-\>getRekapKinerja($filter\_kegiatan),  
            // ... update method lain sesuai kebutuhan ...  
        \];

        return view('monitoring/index', $data);  
    }

## **4\. Tahap 3: Frontend Development (Views)**

### **Langkah 3.1: View Master Kegiatan (app/Views/kegiatan/index.php)**

Gunakan template AdminLTE table.

* Tombol "Tambah" membuka Modal Bootstrap.  
* Tabel menampilkan kolom: No, Nama, Kode, Periode, Status, Aksi.  
* Tombol Aksi: Edit Status (Selesai/Aktif), Hapus.

### **Langkah 3.2: Update View Monitoring (app/Views/monitoring/index.php)**

Tambahkan form filter di bagian atas halaman (sebelum Widgets).

\<form method="get" action="" class="mb-3"\>  
    \<div class="row"\>  
        \<div class="col-md-4"\>  
            \<div class="input-group"\>  
                \<select name="kegiatan" class="form-control" onchange="this.form.submit()"\>  
                    \<option value="all"\>-- Semua Kegiatan \--\</option\>  
                    \<?php foreach($list\_kegiatan as $k): ?\>  
                        \<option value="\<?= $k\['id\_kegiatan'\] ?\>" \<?= ($selected\_kegiatan \== $k\['id\_kegiatan'\]) ? 'selected' : '' ?\>\>  
                            \<?= esc($k\['nama\_kegiatan'\]) ?\>  
                        \</option\>  
                    \<?php endforeach; ?\>  
                \</select\>  
                \<div class="input-group-append"\>  
                    \<button class="btn btn-primary" type="submit"\>Filter\</button\>  
                \</div\>  
            \</div\>  
        \</div\>  
    \</div\>  
\</form\>

## **5\. Tahap 4: Konfigurasi Akhir**

### **Langkah 5.1: Routing (app/Config/Routes.php)**

$routes-\>group('', \['filter' \=\> 'auth'\], function($routes) {  
    // ... route dashboard lama ...  
      
    // Route Master Kegiatan (Hanya Admin \- Role 1\)  
    // Sebaiknya buat filter khusus admin, tapi untuk simpel:  
    $routes-\>get('/kegiatan', 'Kegiatan::index');  
    $routes-\>post('/kegiatan/store', 'Kegiatan::store');  
    $routes-\>post('/kegiatan/status/(:num)', 'Kegiatan::updateStatus/$1');  
    $routes-\>get('/kegiatan/delete/(:num)', 'Kegiatan::delete/$1');  
});

### **Langkah 5.2: Update Sidebar Menu**

Tambahkan link di sidebar layout utama.

\<?php if(session()-\>get('role\_id') \== 1): ?\>  
\<li class="nav-item"\>  
    \<a href="/kegiatan" class="nav-link"\>  
        \<i class="nav-icon fas fa-calendar-alt"\>\</i\>  
        \<p\>Master Kegiatan\</p\>  
    \</a\>  
\</li\>  
\<?php endif; ?\>

## **6\. Tahap 5: Testing Checklist**

| No | Skenario Uji | Ekspektasi | Status |
| :---- | :---- | :---- | :---- |
| 1 | **Create:** Admin menambah kegiatan "Susenas". | Data masuk DB, muncul di tabel. | ⬜ |
| 2 | **Filter:** Admin memilih "Susenas" di Dashboard. | Dashboard me-refresh, angka statistik berubah (kosong jika belum ada data). | ⬜ |
| 3 | **Input:** Petugas input dokumen ke "Susenas". | Dokumen tersimpan dengan id\_kegiatan Susenas. | ⬜ |
| 4 | **Evaluasi:** Cek Dashboard lagi. | Data dokumen baru muncul saat filter "Susenas" aktif. | ⬜ |
| 5 | **Delete:** Hapus kegiatan "Susenas". | Dokumen terkait ikut terhapus (Cascade). | ⬜ |

