# PANDUAN PENGGUNA SISTEM MONIKA
**(Monitoring Nilai Kinerja & Anomali)**

---

## 📋 DAFTAR ISI

1. [Pengantar](#pengantar)
2. [Glosarium](#glosarium)
3. [Persyaratan Sistem](#persyaratan-sistem)
4. [Akses dan Login](#akses-dan-login)
5. [Panduan untuk Administrator](#panduan-untuk-administrator)
6. [Panduan untuk Petugas Pendataan (PCL)](#panduan-untuk-petugas-pendataan-pcl)
7. [Panduan untuk Petugas Pengolahan](#panduan-untuk-petugas-pengolahan)
8. [Panduan untuk Pengawas Lapangan (PML)](#panduan-untuk-pengawas-lapangan-pml)
9. [Panduan untuk Pengawas Pengolahan](#panduan-untuk-pengawas-pengolahan)
10. [Penjelasan Output dan Laporan](#penjelasan-output-dan-laporan)
11. [Troubleshooting](#troubleshooting)
12. [FAQ](#faq)

---

## 🎯 PENGANTAR

### Tujuan Panduan
Panduan ini dirancang untuk membantu semua pengguna sistem MONIKA memahami dan menggunakan aplikasi dengan efektif. Setiap peran pengguna memiliki akses dan fungsi yang berbeda sesuai dengan tanggung jawab mereka dalam proses monitoring kualitas data survei.

### Cakupan Panduan
- Petunjuk penggunaan lengkap untuk semua role pengguna
- Penjelasan detail setiap output dan laporan yang dihasilkan
- Troubleshooting masalah umum
- Best practices untuk setiap peran

### Target Pengguna
- Administrator sistem
- Petugas Pendataan (PCL)
- Petugas Pengolahan
- Pengawas Lapangan (PML)
- Pengawas Pengolahan

---

## 📖 GLOSARIUM

| Istilah | Definisi |
|---------|----------|
| **MONIKA** | Monitoring Nilai Kinerja & Anomali - sistem monitoring kualitas data survei |
| **PCL** | Petugas Pendataan - mitra lapangan yang mengumpulkan data survei |
| **PML** | Pengawas Lapangan - supervisor yang mengawasi tim PCL |
| **Anomali** | Error atau ketidaksesuaian data yang ditemukan dalam dokumen survei |
| **Status Dokumen** | Kondisi terkini dokumen: Uploaded, Sudah Entry, Error, Valid |
| **Pernah Error** | Flag permanen yang menandai dokumen pernah mengalami error |
| **Kegiatan Survei** | Periode atau jenis survei tertentu (contoh: Sakernas, Susenas) |
| **Kode Wilayah** | Identifikasi geografis area survei |
| **Dashboard** | Halaman utama yang menampilkan ringkasan statistik |
| **Entry** | Proses input data dari dokumen fisik ke sistem digital |

---

## 💻 PERSYARATAN SISTEM

### Persyaratan Minimum
- **Browser**: Chrome 80+, Firefox 75+, Safari 13+, Edge 80+
- **Resolusi**: 1024x768 pixels
- **Koneksi Internet**: Stabil untuk akses sistem
- **JavaScript**: Harus diaktifkan

### Rekomendasi
- **Browser**: Chrome atau Firefox versi terbaru
- **Resolusi**: 1366x768 pixels atau lebih tinggi
- **Koneksi**: Broadband untuk performa optimal

---

## 🔐 AKSES DAN LOGIN

### Cara Mengakses Sistem
1. Buka browser web
2. Akses URL: `http://localhost:8080/login` (untuk development)
3. Masukkan username/email dan password
4. Klik tombol "Sign In"

### Proses Login
```
┌─────────────────┐
│   Halaman Login │
└─────────┬───────┘
          │
          ▼
┌─────────────────┐
│ Input Kredensial│
└─────────┬───────┘
          │
          ▼
┌─────────────────┐    ❌ Gagal
│   Validasi      │────────────┐
└─────────┬───────┘            │
          │ ✅ Berhasil         │
          ▼                    │
┌─────────────────┐            │
│   Dashboard     │            │
└─────────────────┘            │
                               │
          ┌────────────────────┘
          ▼
┌─────────────────┐
│ Pesan Error     │
└─────────────────┘
```

### Registrasi Pengguna Baru (Mitra)
1. Klik "Register a new membership (Mitra)" di halaman login
2. Isi form registrasi:
   - Nama Lengkap
   - Username (unik)
   - Email (unik)
   - Password (minimal 6 karakter)
   - Konfirmasi Password
   - NIK KTP (16 digit)
   - SOBAT ID
   - Pilih Role (PCL atau Petugas Pengolahan)
3. Klik "Register"
4. Setelah berhasil, login dengan kredensial yang telah dibuat

---

## 👨‍💼 PANDUAN UNTUK ADMINISTRATOR

### Deskripsi Peran
Administrator memiliki akses penuh ke semua fitur sistem MONIKA. Bertanggung jawab untuk:
- Mengelola master data kegiatan survei
- Monitoring performa seluruh tim
- Mengakses semua laporan dan analisis
- Mengelola pengguna sistem

### Hak Akses Administrator
✅ Dashboard monitoring lengkap  
✅ Manajemen kegiatan survei  
✅ Setor dokumen (jika diperlukan)  
✅ Mark entry dokumen  
✅ Lapor anomali  
✅ Semua laporan kinerja  
✅ Monitoring & evaluasi  

### Langkah-langkah Penggunaan

#### 1. Dashboard Administrator
Setelah login, Anda akan melihat dashboard dengan informasi:

**Widget Statistik:**
- **Total Dokumen Masuk**: Jumlah keseluruhan dokumen yang telah disetor
- **Sudah Entry**: Dokumen yang telah selesai diproses
- **Dokumen Error/Anomali**: Dokumen yang mengalami masalah

**Tabel Ranking:**
- Top 5 Petugas dengan Error Terbanyak
- Data dapat difilter berdasarkan kegiatan survei

#### 2. Mengelola Kegiatan Survei
**Akses:** Menu "Master Kegiatan"

**Membuat Kegiatan Baru:**
1. Klik tombol "Tambah Kegiatan"
2. Isi form:
   - Nama Kegiatan (contoh: "Sakernas Februari 2026")
   - Kode Kegiatan (contoh: "SAK26FEB") - harus unik
   - Tanggal Mulai
   - Tanggal Selesai
   - Status: Aktif/Selesai
3. Klik "Simpan"

**Mengelola Status Kegiatan:**
- Ubah status dari "Aktif" ke "Selesai" ketika periode survei berakhir
- Hanya kegiatan dengan status "Aktif" yang dapat menerima dokumen baru

#### 3. Monitoring & Evaluasi
**Akses:** Menu "Monitoring & Evaluasi"

**Fitur yang Tersedia:**
- Statistik real-time per kegiatan
- Analisis performa tim
- Identifikasi bottleneck proses
- Trend analysis error rate

#### 4. Laporan Kinerja
**Laporan PCL:**
- Akses: Menu "Kinerja PCL"
- Menampilkan produktivitas dan kualitas kerja setiap PCL
- Dapat difilter berdasarkan kegiatan dan periode

**Laporan Pengolahan:**
- Akses: Menu "Kinerja Pengolahan"
- Menampilkan produktivitas petugas pengolahan
- Analisis efisiensi proses entry data

### Tips untuk Administrator
1. **Monitor Dashboard Harian**: Cek statistik setiap hari untuk identifikasi masalah dini
2. **Review Error Patterns**: Analisis jenis error yang sering muncul untuk training
3. **Backup Data**: Pastikan backup database dilakukan secara rutin
4. **User Management**: Monitor aktivitas pengguna dan nonaktifkan akun yang tidak digunakan

---

## 👨‍🔬 PANDUAN UNTUK PETUGAS PENDATAAN (PCL)

### Deskripsi Peran
PCL adalah mitra lapangan yang bertanggung jawab untuk:
- Mengumpulkan data survei di lapangan
- Menyetor dokumen survei ke sistem
- Memantau status dokumen yang telah disetor
- Memperbaiki dokumen yang dikembalikan karena error

### Hak Akses PCL
✅ Dashboard (view only)  
✅ Setor dokumen survei  
✅ Lihat status dokumen sendiri  
❌ Mark entry dokumen  
❌ Lapor anomali  
❌ Laporan kinerja  
❌ Manajemen kegiatan  

### Langkah-langkah Penggunaan

#### 1. Dashboard PCL
Dashboard menampilkan:
- Statistik umum sistem (informasi saja)
- Tidak ada akses ke data detail pengguna lain
- Filter kegiatan untuk melihat statistik spesifik

#### 2. Menyetor Dokumen Survei
**Akses:** Menu "Dokumen Survei" → "Setor Dokumen"

**Langkah-langkah:**
1. Klik tombol "Setor Dokumen"
2. Isi form penyetoran:
   - **Kegiatan**: Pilih kegiatan survei yang aktif
   - **Kode Wilayah**: Masukkan kode wilayah sesuai area survei
   - **Tanggal Setor**: Pilih tanggal penyetoran
3. Klik "Simpan"
4. Sistem akan otomatis:
   - Mencatat Anda sebagai petugas pendataan
   - Mengatur status dokumen menjadi "Uploaded"
   - Memberikan ID dokumen unik

#### 3. Memantau Status Dokumen
**Akses:** Menu "Dokumen Survei"

**Informasi yang Ditampilkan:**
- ID Dokumen
- Kegiatan terkait
- Kode Wilayah
- Tanggal Setor
- Status terkini
- Nama petugas pengolahan (jika sudah diproses)

**Status Dokumen yang Mungkin:**
- 🔵 **Uploaded**: Dokumen baru disetor, menunggu diproses
- 🟢 **Sudah Entry**: Dokumen telah selesai diproses tanpa masalah
- 🔴 **Error**: Dokumen dikembalikan karena ada anomali
- ✅ **Valid**: Dokumen yang pernah error telah diperbaiki dan diterima

#### 4. Menangani Dokumen Error
Jika dokumen berstatus "Error":
1. Hubungi petugas pengolahan untuk detail error
2. Perbaiki dokumen sesuai keterangan error
3. Setor ulang dokumen yang telah diperbaiki
4. Dokumen akan diproses ulang oleh petugas pengolahan

### Tips untuk PCL
1. **Cek Kualitas Sebelum Setor**: Pastikan dokumen lengkap dan terbaca
2. **Catat ID Dokumen**: Simpan ID dokumen untuk tracking
3. **Follow Up Error**: Segera tindaklanjuti dokumen yang berstatus error
4. **Konsistensi Kode Wilayah**: Gunakan format kode wilayah yang konsisten

---

## 👨‍💻 PANDUAN UNTUK PETUGAS PENGOLAHAN

### Deskripsi Peran
Petugas Pengolahan bertanggung jawab untuk:
- Memproses dokumen yang telah disetor PCL
- Melakukan entry data dari dokumen fisik
- Mengidentifikasi dan melaporkan anomali
- Memvalidasi dokumen yang telah diperbaiki

### Hak Akses Petugas Pengolahan
✅ Dashboard (view only)  
✅ Lihat semua dokumen  
✅ Mark entry dokumen  
✅ Lapor anomali  
❌ Setor dokumen  
❌ Laporan kinerja  
❌ Manajemen kegiatan  

### Langkah-langkah Penggunaan

#### 1. Dashboard Petugas Pengolahan
Dashboard menampilkan statistik umum untuk referensi kerja:
- Total dokumen yang perlu diproses
- Jumlah dokumen yang sudah selesai
- Jumlah dokumen error yang perlu ditindaklanjuti

#### 2. Memproses Dokumen
**Akses:** Menu "Dokumen Survei"

**Workflow Pemrosesan:**
```
Dokumen "Uploaded" → Review → Keputusan:
                                ├─ Valid → Mark "Sudah Entry"
                                └─ Ada Masalah → "Lapor Error"
```

**Langkah-langkah:**
1. Buka daftar dokumen
2. Fokus pada dokumen dengan status "Uploaded"
3. Review dokumen fisik/digital
4. Buat keputusan:

#### 3. Mark Entry (Dokumen Valid)
Jika dokumen tidak ada masalah:
1. Klik tombol "Entry" pada dokumen yang bersangkutan
2. Konfirmasi dengan klik "OK" pada dialog konfirmasi
3. Sistem akan:
   - Mengubah status menjadi "Sudah Entry"
   - Mencatat Anda sebagai processor
   - Menandai waktu pemrosesan

#### 4. Melaporkan Anomali
Jika ditemukan masalah pada dokumen:
1. Klik tombol "Error" pada dokumen yang bermasalah
2. Modal "Laporkan Anomali" akan terbuka
3. Isi form:
   - **Jenis Error**: Pilih kategori error
     - Data Tidak Lengkap
     - Format Salah
     - Inkonsistensi Data
     - Lainnya
   - **Keterangan Detail**: Jelaskan masalah secara spesifik
4. Klik "Laporkan Error"
5. Sistem akan:
   - Mengubah status dokumen menjadi "Error"
   - Mencatat log anomali
   - Mengatur flag "pernah_error" = 1 (permanen)
   - Memberitahu PCL untuk perbaikan

#### 5. Memproses Dokumen yang Diperbaiki
Untuk dokumen yang telah diperbaiki PCL:
1. Review dokumen yang statusnya "Error"
2. Jika sudah diperbaiki dengan baik:
   - Klik "Entry" untuk mark sebagai "Valid"
3. Jika masih ada masalah:
   - Laporkan error lagi dengan keterangan baru

### Output yang Dihasilkan

#### Log Anomali
Setiap laporan error akan tercatat dengan informasi:
- ID Anomali (auto-generated)
- ID Dokumen terkait
- Petugas yang melaporkan
- Jenis error
- Keterangan detail
- Timestamp pelaporan

#### Status Tracking
Sistem akan mencatat:
- Siapa yang memproses dokumen
- Kapan dokumen diproses
- Riwayat perubahan status
- Flag permanen untuk dokumen yang pernah error

### Tips untuk Petugas Pengolahan
1. **Konsistensi Kriteria**: Gunakan standar yang sama untuk semua dokumen
2. **Detail Error Report**: Berikan keterangan yang jelas dan actionable
3. **Prioritas Processing**: Proses dokumen berdasarkan tanggal setor (FIFO)
4. **Komunikasi dengan PCL**: Koordinasi untuk klarifikasi jika diperlukan

---

## 👨‍🏫 PANDUAN UNTUK PENGAWAS LAPANGAN (PML)

### Deskripsi Peran
PML bertanggung jawab untuk:
- Mengawasi kinerja tim PCL
- Monitoring kualitas data yang dikumpulkan
- Memberikan feedback dan coaching kepada PCL
- Melaporkan performa tim kepada manajemen

### Hak Akses PML
✅ Dashboard monitoring  
✅ Lihat dokumen tim  
✅ Laporan kinerja PCL  
✅ Monitoring & evaluasi  
❌ Setor dokumen  
❌ Mark entry  
❌ Lapor anomali  
❌ Manajemen kegiatan  

### Langkah-langkah Penggunaan

#### 1. Dashboard PML
Dashboard menampilkan:
- Statistik performa tim secara agregat
- Ranking error contributors
- Trend kinerja tim
- Filter berdasarkan kegiatan untuk analisis spesifik

#### 2. Monitoring Tim PCL
**Akses:** Menu "Laporan" → "Kinerja PCL"

**Informasi yang Tersedia:**
- Daftar semua PCL dalam tim
- Produktivitas (jumlah dokumen disetor)
- Kualitas (tingkat error)
- Perbandingan performa antar anggota tim

#### 3. Analisis Performa
**Metrik Kinerja PCL:**
- **Total Dokumen**: Jumlah dokumen yang disetor
- **Total Error**: Jumlah dokumen yang mengalami error
- **Error Rate**: Persentase error dari total dokumen
- **Trend**: Perkembangan kinerja dari waktu ke waktu

#### 4. Tindak Lanjut
Berdasarkan analisis, PML dapat:
- Memberikan coaching kepada PCL dengan error rate tinggi
- Mengidentifikasi best practices dari PCL terbaik
- Melaporkan kebutuhan training tambahan
- Koordinasi dengan manajemen untuk resource allocation

### Tips untuk PML
1. **Review Berkala**: Monitor performa tim secara rutin
2. **Feedback Konstruktif**: Berikan masukan yang membangun
3. **Identifikasi Pattern**: Cari pola error untuk training focused
4. **Dokumentasi**: Catat progress dan improvement tim

---

## 👨‍💼 PANDUAN UNTUK PENGAWAS PENGOLAHAN

### Deskripsi Peran
Pengawas Pengolahan bertanggung jawab untuk:
- Mengawasi kinerja tim petugas pengolahan
- Monitoring efisiensi proses entry data
- Memastikan konsistensi quality control
- Optimasi workflow pengolahan

### Hak Akses Pengawas Pengolahan
✅ Dashboard monitoring  
✅ Lihat semua dokumen  
✅ Laporan kinerja pengolahan  
✅ Monitoring & evaluasi  
❌ Setor dokumen  
❌ Mark entry  
❌ Lapor anomali  
❌ Manajemen kegiatan  

### Langkah-langkah Penggunaan

#### 1. Dashboard Pengawas
Dashboard menampilkan:
- Throughput pengolahan harian
- Backlog dokumen yang menunggu
- Distribusi beban kerja tim
- Quality metrics

#### 2. Monitoring Tim Pengolahan
**Akses:** Menu "Laporan" → "Kinerja Pengolahan"

**Metrik yang Dipantau:**
- **Produktivitas**: Jumlah dokumen yang diproses per hari
- **Akurasi**: Tingkat konsistensi dalam quality control
- **Efisiensi**: Waktu rata-rata pemrosesan per dokumen
- **Workload Balance**: Distribusi beban kerja antar tim

#### 3. Analisis Workflow
Pengawas dapat menganalisis:
- Bottleneck dalam proses
- Pola error yang sering ditemukan
- Efektivitas quality control
- Kebutuhan resource tambahan

### Tips untuk Pengawas Pengolahan
1. **Monitor Backlog**: Pastikan tidak ada penumpukan dokumen
2. **Standardisasi Proses**: Pastikan konsistensi kriteria quality control
3. **Load Balancing**: Distribusikan beban kerja secara merata
4. **Continuous Improvement**: Identifikasi area untuk optimasi proses

---

## 📊 PENJELASAN OUTPUT DAN LAPORAN

### Dashboard Statistik

#### Widget Total Dokumen Masuk
**Format Output:**
```
┌─────────────────────┐
│        1,234        │
│ Total Dokumen Masuk │
└─────────────────────┘
```

**Interpretasi:**
- Angka menunjukkan jumlah kumulatif dokumen yang telah disetor
- Termasuk semua status: Uploaded, Sudah Entry, Error, Valid
- Data dapat difilter berdasarkan kegiatan survei

#### Widget Sudah Entry
**Format Output:**
```
┌─────────────────────┐
│         987         │
│    Sudah Entry      │
└─────────────────────┘
```

**Interpretasi:**
- Dokumen yang telah selesai diproses tanpa masalah
- Indikator produktivitas tim pengolahan
- Target: 100% dari dokumen yang disetor

#### Widget Dokumen Error/Anomali
**Format Output:**
```
┌─────────────────────┐
│         123         │
│ Dokumen Error/Anomali│
└─────────────────────┘
```

**Interpretasi:**
- Dokumen yang saat ini berstatus "Error"
- Memerlukan tindak lanjut dari PCL
- Target: <5% dari total dokumen

### Tabel Ranking Error

**Format Output:**
```
┌────┬─────────────────┬──────────────┐
│ #  │   Nama PCL      │ Jumlah Error │
├────┼─────────────────┼──────────────┤
│ 1  │ Ahmad Santoso   │      15      │
│ 2  │ Siti Rahayu     │      12      │
│ 3  │ Budi Prakoso    │      10      │
│ 4  │ Dewi Lestari    │       8      │
│ 5  │ Eko Wijaya      │       7      │
└────┴─────────────────┴──────────────┘
```

**Interpretasi:**
- Ranking berdasarkan jumlah dokumen error
- Digunakan untuk identifikasi PCL yang memerlukan coaching
- Data dapat difilter per kegiatan survei

### Laporan Kinerja PCL

**Format Output:**
```
┌─────────────────┬─────────────┬─────────────┬────────────┐
│   Nama PCL      │ Total Dok   │ Total Error │ Error Rate │
├─────────────────┼─────────────┼─────────────┼────────────┤
│ Ahmad Santoso   │     100     │      5      │    5.0%    │
│ Siti Rahayu     │      85     │      3      │    3.5%    │
│ Budi Prakoso    │     120     │      8      │    6.7%    │
└─────────────────┴─────────────┴─────────────┴────────────┘
```

**Interpretasi Kolom:**
- **Total Dok**: Produktivitas - jumlah dokumen yang disetor
- **Total Error**: Jumlah dokumen yang pernah mengalami error
- **Error Rate**: Persentase kualitas (semakin rendah semakin baik)

**Benchmark:**
- Error Rate < 3%: Excellent
- Error Rate 3-5%: Good
- Error Rate 5-10%: Needs Improvement
- Error Rate > 10%: Requires Training

### Laporan Kinerja Pengolahan

**Format Output:**
```
┌─────────────────┬─────────────┬─────────────────┐
│ Nama Processor  │ Total Entry │ Temuan Error    │
├─────────────────┼─────────────┼─────────────────┤
│ Rina Sari       │     150     │       25        │
│ Joko Susilo     │     130     │       20        │
│ Maya Indah      │     140     │       30        │
└─────────────────┴─────────────┴─────────────────┘
```

**Interpretasi Kolom:**
- **Total Entry**: Produktivitas - dokumen yang diproses
- **Temuan Error**: Jumlah anomali yang berhasil diidentifikasi

**Benchmark:**
- Total Entry > 100/hari: High Productivity
- Total Entry 50-100/hari: Normal Productivity
- Total Entry < 50/hari: Low Productivity

### Status Dokumen Detail

**Format Output:**
```
┌─────┬─────────────┬─────────────┬─────────────┬─────────────┬─────────────┐
│ ID  │  Kegiatan   │   Wilayah   │     PCL     │ Tgl Setor   │   Status    │
├─────┼─────────────┼─────────────┼─────────────┼─────────────┼─────────────┤
│ 001 │ SAK26FEB    │ 3201001     │ Ahmad S.    │ 2026-01-15  │ 🔵 Uploaded │
│ 002 │ SAK26FEB    │ 3201002     │ Siti R.     │ 2026-01-15  │ ✅ Valid    │
│ 003 │ SAK26FEB    │ 3201003     │ Budi P.     │ 2026-01-16  │ 🔴 Error    │
└─────┴─────────────┴─────────────┴─────────────┴─────────────┴─────────────┘
```

**Interpretasi Status:**
- 🔵 **Uploaded**: Baru disetor, menunggu diproses
- 🟢 **Sudah Entry**: Selesai diproses, tidak ada masalah
- 🔴 **Error**: Ada masalah, perlu perbaikan PCL
- ✅ **Valid**: Sudah diperbaiki dan diterima

### Log Anomali

**Format Output:**
```
┌─────────┬─────────────┬─────────────────┬─────────────────────────────┐
│ ID Dok  │   Tanggal   │   Jenis Error   │         Keterangan          │
├─────────┼─────────────┼─────────────────┼─────────────────────────────┤
│  003    │ 2026-01-16  │ Data Tidak      │ Kolom umur tidak diisi      │
│         │ 14:30       │ Lengkap         │ pada responden nomor 5      │
├─────────┼─────────────┼─────────────────┼─────────────────────────────┤
│  007    │ 2026-01-16  │ Format Salah    │ Kode pekerjaan menggunakan  │
│         │ 15:45       │                 │ format lama                 │
└─────────┴─────────────┴─────────────────┴─────────────────────────────┘
```

**Interpretasi:**
- **ID Dok**: Referensi ke dokumen yang bermasalah
- **Tanggal**: Waktu pelaporan error
- **Jenis Error**: Kategori masalah yang ditemukan
- **Keterangan**: Detail spesifik untuk perbaikan

---

## 🔧 TROUBLESHOOTING

### Masalah Login

#### Problem: "Username/Email tidak ditemukan"
**Penyebab:**
- Username atau email salah
- Akun belum terdaftar

**Solusi:**
1. Periksa kembali username/email
2. Jika belum terdaftar, lakukan registrasi
3. Hubungi administrator jika masih bermasalah

#### Problem: "Password salah"
**Penyebab:**
- Password tidak sesuai
- Caps Lock aktif

**Solusi:**
1. Periksa Caps Lock
2. Ketik ulang password dengan hati-hati
3. Reset password jika lupa

#### Problem: "Akun Anda non-aktif"
**Penyebab:**
- Akun dinonaktifkan oleh administrator

**Solusi:**
1. Hubungi administrator untuk aktivasi akun
2. Pastikan tidak ada pelanggaran kebijakan

### Masalah Akses Menu

#### Problem: Menu tidak muncul
**Penyebab:**
- Role tidak memiliki akses
- Session expired

**Solusi:**
1. Logout dan login kembali
2. Periksa role akun Anda
3. Hubungi administrator jika perlu perubahan role

### Masalah Input Data

#### Problem: "Validation Error"
**Penyebab:**
- Data tidak sesuai format
- Field wajib tidak diisi

**Solusi:**
1. Periksa semua field yang ditandai merah
2. Pastikan format data sesuai (tanggal, angka, dll.)
3. Isi semua field yang wajib

#### Problem: "Kode kegiatan sudah ada"
**Penyebab:**
- Kode kegiatan tidak unik

**Solusi:**
1. Gunakan kode yang berbeda
2. Periksa daftar kegiatan yang sudah ada

### Masalah Performa

#### Problem: Halaman loading lambat
**Penyebab:**
- Koneksi internet lambat
- Server overload

**Solusi:**
1. Periksa koneksi internet
2. Refresh halaman
3. Coba akses di waktu yang berbeda

#### Problem: Data tidak muncul
**Penyebab:**
- Filter terlalu spesifik
- Tidak ada data sesuai kriteria

**Solusi:**
1. Reset filter
2. Periksa periode waktu yang dipilih
3. Hubungi administrator jika data seharusnya ada

---

## ❓ FAQ (Frequently Asked Questions)

### Umum

**Q: Bagaimana cara mengubah password?**
A: Saat ini fitur ubah password belum tersedia. Hubungi administrator untuk reset password.

**Q: Apakah sistem bisa diakses dari mobile?**
A: Ya, sistem responsive dan dapat diakses dari browser mobile, namun pengalaman terbaik di desktop.

**Q: Bagaimana jika lupa username?**
A: Hubungi administrator dengan menyebutkan nama lengkap dan email yang terdaftar.

### Untuk PCL

**Q: Berapa lama dokumen diproses setelah disetor?**
A: Tergantung beban kerja tim pengolahan, umumnya 1-3 hari kerja.

**Q: Apa yang harus dilakukan jika dokumen berstatus error?**
A: Lihat keterangan error di log anomali, perbaiki dokumen, lalu setor ulang.

**Q: Bisakah mengedit dokumen yang sudah disetor?**
A: Tidak bisa edit, harus setor dokumen baru jika ada perbaikan.

### Untuk Petugas Pengolahan

**Q: Bagaimana standar menentukan dokumen error?**
A: Ikuti panduan quality control yang diberikan supervisor dan training.

**Q: Apa yang dilakukan jika ragu dengan kualitas dokumen?**
A: Konsultasi dengan supervisor atau pengawas pengolahan.

**Q: Bisakah membatalkan laporan error?**
A: Tidak bisa dibatalkan, namun dokumen bisa dimark valid setelah diperbaiki.

### Untuk Supervisor

**Q: Seberapa sering harus monitoring tim?**
A: Disarankan daily monitoring untuk identifikasi masalah dini.

**Q: Bagaimana cara export data laporan?**
A: Fitur export belum tersedia, gunakan screenshot atau copy data manual.

**Q: Apa benchmark error rate yang baik?**
A: Target error rate < 5%, excellent jika < 3%.

---

## 📞 KONTAK SUPPORT

### Tim Support Teknis
- **Email**: support@monika-system.com
- **Telepon**: (021) 1234-5678
- **Jam Kerja**: Senin-Jumat, 08:00-17:00 WIB

### Escalation Matrix
1. **Level 1**: User manual dan FAQ
2. **Level 2**: Supervisor/Pengawas
3. **Level 3**: Administrator sistem
4. **Level 4**: Tim IT/Developer

---

**Dokumen ini dibuat pada:** 29 Januari 2026  
**Versi:** 1.0  
**Status:** Lengkap dan siap digunakan  
**Update Terakhir:** 29 Januari 2026

---

*Panduan ini akan diperbarui secara berkala sesuai dengan pengembangan sistem dan feedback pengguna.*