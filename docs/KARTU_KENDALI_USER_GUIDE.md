# Panduan Pengguna - Modul Kartu Kendali Digital

## Pengenalan
Modul Kartu Kendali Digital adalah sistem untuk melaporkan hasil entry data survei per rumah tangga (ruta). Setiap NKS memiliki maksimal 10 ruta yang harus dikerjakan.

## Cara Menggunakan

### 1. Mengakses Modul
1. Login ke aplikasi MONIKA
2. Klik menu **"Kartu Kendali"** di sidebar
3. Anda akan melihat daftar NKS yang tersedia

### 2. Memahami Halaman Daftar NKS

#### Kolom-kolom Tabel:
- **NKS**: Kode Nomor Kode Sampel
- **Kecamatan**: Lokasi kecamatan
- **Desa**: Lokasi desa
- **Diterima**: Jumlah ruta yang sudah diterima dari logistik
- **Progres Entry**: Progress bar menunjukkan berapa ruta yang sudah selesai
- **Aksi**: Tombol untuk membuka detail entry

#### Warna Progress Bar:
- 🔴 **Merah**: 0-49% selesai
- 🟡 **Kuning**: 50-99% selesai
- 🟢 **Hijau**: 100% selesai

### 3. Membuka Detail NKS
1. Klik tombol **"Buka Rincian"** pada NKS yang ingin dikerjakan
2. Anda akan melihat grid 10 kotak yang merepresentasikan ruta 1-10

### 4. Memahami Status Kotak Ruta

#### 🔲 Abu-abu (Belum Diterima)
- Dokumen fisik belum diterima dari logistik
- Tidak bisa dikerjakan
- Tunggu hingga dokumen diterima

#### 🟨 Kuning (Dikerjakan Petugas Lain)
- Ruta ini sedang dikerjakan oleh petugas lain
- Menampilkan nama petugas yang mengerjakan
- Tidak bisa dikerjakan oleh Anda

#### 🟩 Hijau (Selesai - Clean)
- Ruta sudah selesai dikerjakan
- Status: Clean (tidak ada error)
- Jika milik Anda, bisa diedit atau dihapus

#### 🟥 Merah (Selesai - Error)
- Ruta sudah selesai dikerjakan
- Status: Error (ada kesalahan)
- Jika milik Anda, bisa diedit atau dihapus

#### ⬜ Putih (Siap Dikerjakan)
- Dokumen sudah diterima
- Belum ada yang mengerjakan
- Klik untuk mulai entry

### 5. Melakukan Entry Data

#### Langkah-langkah:
1. Klik kotak ruta yang berwarna **putih** (siap dikerjakan)
2. Modal form akan muncul
3. Pilih **Status Entry**:
   - **Clean**: Jika tidak ada error dalam entry
   - **Error**: Jika ada kesalahan/anomali
4. Centang **"Masalah Aplikasi"** jika:
   - Data sudah benar
   - Tapi aplikasi entry bermasalah (patch issue)
5. Klik tombol **"Simpan"**
6. Kotak akan berubah warna sesuai status

### 6. Mengedit Data Entry

#### Jika Anda ingin mengubah entry:
1. Klik kotak ruta yang sudah Anda kerjakan (hijau/merah)
2. Modal form akan muncul dengan data yang sudah ada
3. Ubah status atau patch issue sesuai kebutuhan
4. Klik **"Simpan"** untuk update

### 7. Menghapus Data Entry

#### Jika Anda ingin membatalkan entry:
1. Klik kotak ruta yang sudah Anda kerjakan
2. Klik tombol **"Hapus"** di modal
3. Konfirmasi penghapusan
4. Kotak akan kembali menjadi putih (siap dikerjakan)

## Tips & Trik

### ✅ Do's (Yang Harus Dilakukan)
- Pastikan status entry sesuai dengan kondisi sebenarnya
- Gunakan checkbox "Masalah Aplikasi" hanya jika memang ada bug aplikasi
- Periksa kembali sebelum menyimpan
- Koordinasi dengan tim jika ada ruta yang bermasalah

### ❌ Don'ts (Yang Tidak Boleh Dilakukan)
- Jangan mengklaim ruta yang sedang dikerjakan orang lain
- Jangan menandai Clean jika masih ada error
- Jangan lupa menyimpan data setelah entry
- Jangan menutup browser sebelum data tersimpan

## Troubleshooting

### Masalah: Tombol "Entry" tidak bisa diklik
**Solusi:**
- Pastikan dokumen sudah diterima dari logistik
- Cek apakah ruta sudah dikerjakan petugas lain
- Refresh halaman jika masih bermasalah

### Masalah: Data tidak tersimpan
**Solusi:**
- Pastikan koneksi internet stabil
- Cek apakah semua field sudah diisi
- Coba logout dan login kembali

### Masalah: Tidak bisa mengedit data orang lain
**Solusi:**
- Ini adalah fitur keamanan
- Hanya bisa edit data entry milik sendiri
- Hubungi admin jika perlu mengubah data orang lain

### Masalah: Progress bar tidak update
**Solusi:**
- Refresh halaman (F5)
- Clear cache browser
- Logout dan login kembali

## FAQ (Frequently Asked Questions)

**Q: Berapa maksimal ruta per NKS?**
A: Maksimal 10 ruta per NKS.

**Q: Apa bedanya Clean dan Error?**
A: Clean = tidak ada kesalahan, Error = ada kesalahan dalam entry data.

**Q: Apa itu Patch Issue?**
A: Masalah pada aplikasi entry, bukan kesalahan data. Centang jika aplikasi bermasalah tapi data sudah benar.

**Q: Bisa mengerjakan ruta yang sudah dikerjakan orang lain?**
A: Tidak bisa. Satu ruta hanya bisa dikerjakan oleh satu petugas.

**Q: Bagaimana jika salah entry?**
A: Klik kotak ruta yang sudah dikerjakan, lalu edit atau hapus.

**Q: Apakah data bisa dihapus?**
A: Ya, tapi hanya data entry milik sendiri.

**Q: Bagaimana cara melihat siapa yang mengerjakan ruta tertentu?**
A: Kotak kuning akan menampilkan nama petugas yang mengerjakan.

## Kontak Support
Jika mengalami masalah teknis, hubungi:
- Admin Sistem MONIKA
- Tim IT BPS Jember
- Email: support@bpsjember.go.id

---
**Versi Dokumen**: 1.0  
**Terakhir Diperbarui**: 15 Februari 2026  
**Dibuat oleh**: Tim Pengembang MONIKA
