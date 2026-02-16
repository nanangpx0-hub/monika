# Panduan Penggunaan per Role — MONIKA

> **Terakhir diperbarui**: 17 Februari 2026

---

## 1. Administrator (Role 1)

### Deskripsi
Administrator memiliki akses penuh ke seluruh fitur MONIKA, termasuk manajemen pengguna, kegiatan, laporan, dan monitoring.

### Fitur yang Tersedia

#### 1.1 Kelola Pengguna
1. Buka menu **Kelola Pengguna** di sidebar
2. Klik **Tambah Pengguna** untuk membuat akun baru
3. Isi form: Nama Lengkap, Username, Email, Password, Role, NIK, Sobat ID
4. Untuk mengubah data → klik **Edit** pada baris pengguna
5. Untuk reset password → klik **Reset Password**
6. Untuk menonaktifkan → klik **Toggle Aktif**

> [!CAUTION]
> Admin tidak dapat menghapus atau menonaktifkan akun sendiri (proteksi diri).

#### 1.2 Kelola Kegiatan
1. Buka menu **Kegiatan** di sidebar
2. Klik **Tambah Kegiatan** → isi nama, kode (auto-generate), tanggal mulai/selesai
3. Ubah status kegiatan: **Aktif** ↔ **Selesai**
4. Hapus kegiatan yang sudah tidak diperlukan

#### 1.3 Laporan & Monitoring
1. Menu **Laporan** → Dashboard chart + tabel + ekspor (Excel/PDF)
2. Menu **Monitoring** → Ringkasan progress keseluruhan
3. Laporan PCL → performa per petugas pendataan
4. Laporan Pengolahan → performa per petugas pengolahan

#### 1.4 Semua Fitur Operasional
Admin dapat mengakses semua fitur yang tersedia untuk role lain (lihat bagian 2–5).

### FAQ Admin
| Pertanyaan | Jawaban |
|------------|---------|
| Bagaimana menambah role baru? | Tambahkan record di tabel `roles`, lalu sesuaikan pengecekan `id_role` di controller terkait |
| User lupa password? | Gunakan fitur **Reset Password** di Kelola Pengguna |
| Bagaimana melihat audit trail? | Data tersimpan di tabel `user_audit_log`, dapat dilihat via database |

---

## 2. Petugas Pendataan / PCL (Role 3)

### Deskripsi
PCL adalah mitra lapangan yang bertanggung jawab mengumpulkan data survei dari responden dan menyetor dokumen ke sistem.

### Fitur yang Tersedia

#### 2.1 Setor Dokumen
1. Buka menu **Dokumen** di sidebar
2. Klik **Setor Dokumen** → isi Kegiatan, Kode Wilayah, Tanggal Setor
3. Atau klik **Impor Excel** → upload file `.xlsx` dengan data kode wilayah
4. Dokumen tersimpan dengan status **"Uploaded"**

#### 2.2 Penyetoran Dokumen (ke Tim PLS)
1. Buka menu **Penyetoran Dokumen** di sidebar
2. Klik **Setor Dokumen Baru**
3. Isi informasi penyerahan: kegiatan, jenis dokumen, nama pengirim, tanggal
4. Tambah detail per ruta (manual atau impor Excel)
5. Klik **Setor Dokumen** → status menjadi **"Diserahkan"**
6. Tunggu konfirmasi dari Tim PLS

#### 2.3 Fitur Lainnya
- **Tanda Terima** → input penerimaan dokumen per NKS
- **Presensi** → absensi harian
- **Kartu Kendali** → lihat progress (read only)
- **Logistik** → lihat stok barang

### Batasan PCL
- ❌ Tidak dapat menandai "Sudah Entry" atau "Error"
- ❌ Tidak dapat mengakses Kelola Pengguna, Kegiatan, Laporan, Monitoring
- ❌ Dokumen yang ditampilkan hanya milik sendiri

### FAQ PCL
| Pertanyaan | Jawaban |
|------------|---------|
| Dokumen saya tidak muncul di daftar? | Pastikan Anda login dengan akun yang benar. PCL hanya melihat dokumen milik sendiri |
| Bagaimana mengetahui status setoran? | Buka menu Penyetoran Dokumen → cek kolom Status |
| Template Excel format-nya bagaimana? | Klik "Download Template" di halaman form impor |

---

## 3. Petugas Pengolahan (Role 4)

### Deskripsi
Petugas Pengolahan bertanggung jawab memproses dokumen survei yang telah disetor oleh PCL, melakukan entry data, dan validasi.

### Fitur yang Tersedia

#### 3.1 Entry Dokumen
1. Buka menu **Dokumen** di sidebar
2. Pada dokumen berstatus **"Uploaded"**, klik **Entry** untuk menandai sudah di-entry
3. Atau klik **Error** untuk melaporkan kesalahan → isi jenis error dan keterangan

#### 3.2 Konfirmasi Penyetoran
1. Buka menu **Penyetoran Dokumen**
2. Klik **Detail** pada penyetoran berstatus **"Diserahkan"**
3. Review daftar dokumen per ruta
4. Klik **Terima** atau **Tolak** + isi catatan

#### 3.3 Kartu Kendali
1. Buka menu **Kartu Kendali** → lihat progress per NKS
2. Klik detail NKS → input entry per ruta (No Ruta, Status Clean/Error, Tanggal)

#### 3.4 Fitur Lainnya
- **Presensi** → absensi harian
- **Tanda Terima** → lihat data
- **Logistik** → lihat stok barang

### Batasan Petugas Pengolahan
- ❌ Tidak dapat menyetor dokumen baru
- ❌ Tidak dapat mengakses Kelola Pengguna, Kegiatan, Laporan, Monitoring

### FAQ Petugas Pengolahan
| Pertanyaan | Jawaban |
|------------|---------|
| Bagaimana melaporkan error dokumen? | Klik tombol **Error** pada dokumen berstatus "Uploaded" |
| Apakah bisa membatalkan entry? | Saat ini belum ada fitur pembatalan entry |

---

## 4. Pengawas Lapangan / PML (Role 5)

### Deskripsi
PML mengawasi kinerja PCL di lapangan dan melakukan uji petik untuk memastikan kualitas pendataan.

### Fitur yang Tersedia

#### 4.1 Uji Petik
1. Buka menu **Uji Petik** di sidebar
2. Klik **Tambah Uji Petik**
3. Isi: NKS, No Ruta, Variabel, Isian Kuesioner (K), Isian Corrected (C), Alasan Kesalahan
4. Edit atau hapus data uji petik yang sudah ada

#### 4.2 Montioring Bawahan
- Lihat daftar dokumen semua PCL di wilayah supervisi
- Pantau progress pendataan via Kartu Kendali
- Review tanda terima dokumen

### Batasan PML
- ❌ Tidak dapat menyetor atau entry dokumen
- ❌ Tidak dapat mengakses modul manajemen

---

## 5. Pengawas Pengolahan (Role 6)

### Deskripsi  
Pengawas Pengolahan mengawasi kinerja Petugas Pengolahan dan melakukan quality control proses entry data.

### Fitur yang Tersedia

#### 5.1 Uji Petik
Sama dengan PML — dapat menambah, mengedit, dan menghapus data uji petik.

#### 5.2 Monitoring Pengolahan
- Lihat progress entry semua petugas pengolahan
- Pantau Kartu Kendali untuk deteksi bottleneck
- Review anomali log

### Batasan Pengawas Pengolahan
- ❌ Tidak dapat menyetor atau entry dokumen
- ❌ Tidak dapat mengakses modul manajemen

---

## Troubleshooting Umum (Semua Role)

| Masalah | Solusi |
|---------|--------|
| Tidak bisa login | Pastikan username & password benar. Cek apakah akun masih aktif |
| Menu tidak muncul di sidebar | Menu tertentu hanya muncul untuk role tertentu. Hubungi admin |
| Error "Akses ditolak" | Fitur tidak tersedia untuk role Anda |
| Halaman kosong / error 500 | Hubungi administrator untuk pengecekan log server |
| Data tidak tersimpan | Pastikan semua field wajib (*) sudah diisi dengan format yang benar |
