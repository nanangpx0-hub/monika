# Panduan Pengguna Kartu Kendali Digital

## Tujuan
Panduan ini membantu petugas pengolahan menggunakan modul Kartu Kendali untuk mencatat status entry ruta pada setiap NKS.

## Ringkasan Alur
1. Buka menu `Kartu Kendali`.
2. Pilih NKS pada daftar.
3. Klik ruta yang siap dikerjakan.
4. Pilih status `Clean` atau `Error`, lalu simpan.
5. Edit atau hapus data jika entry tersebut milik Anda.

## Akses Modul
1. Login ke aplikasi MONIKA.
2. Pada sidebar, buka menu `Kartu Kendali`.
3. Halaman daftar NKS akan menampilkan progres entry per NKS.

## Halaman Daftar NKS
Kolom utama pada tabel:
- `NKS`: kode sampel.
- `Kecamatan` dan `Desa`: lokasi wilayah.
- `Ruta Diterima`: jumlah ruta yang sudah diterima dari logistik.
- `Progress Entry`: jumlah ruta yang sudah di-entry dibanding ruta diterima.
- `Aksi`: tombol `Buka` ke halaman detail NKS.

Warna progress bar:
- Merah: progres 0-49%.
- Kuning: progres 50-99%.
- Hijau: progres 100%.

## Halaman Detail NKS
Pada halaman detail, tersedia grid ruta 1 sampai 10.

Makna warna status ruta:
- Abu-abu: dokumen belum diterima dari logistik, tidak dapat di-entry.
- Kuning: ruta sudah dikerjakan petugas lain, terkunci.
- Hijau: selesai dengan status `Clean`.
- Merah: selesai dengan status `Error`.
- Putih: siap dikerjakan.

## Cara Input Data Entry
1. Klik tombol `Entry` pada ruta putih.
2. Pada modal form, pilih `Status Entry`:
   - `Clean` untuk data tanpa masalah.
   - `Error` untuk data yang masih bermasalah.
3. Centang `Masalah Aplikasi (Patch Issue)` jika masalah ada di aplikasi, bukan pada data.
4. Klik `Simpan`.

## Cara Edit Data
1. Klik tombol `Edit` pada ruta yang pernah Anda isi (hijau atau merah).
2. Ubah nilai status sesuai kebutuhan.
3. Klik `Simpan`.

## Cara Hapus Data
1. Buka ruta milik Anda melalui tombol `Edit`.
2. Klik tombol `Hapus`.
3. Konfirmasi penghapusan.

Setelah dihapus, ruta kembali menjadi status siap dikerjakan.

## Aturan Penting
- Satu ruta hanya dapat dimiliki satu petugas.
- Anda tidak dapat mengubah data milik petugas lain.
- Simpan perubahan sebelum pindah halaman.

## Troubleshooting Singkat
Masalah umum dan tindakan cepat:
- Tombol tidak bisa diklik: cek status warna ruta, lalu refresh halaman.
- Data gagal tersimpan: cek koneksi, pastikan status entry sudah dipilih, lalu coba ulang.
- Tidak bisa edit data tertentu: data tersebut bukan milik akun Anda.
- Progress belum berubah: refresh halaman detail dan daftar NKS.

## FAQ
**Berapa ruta maksimal per NKS?**  
Maksimal 10 ruta.

**Apa beda `Clean` dan `Error`?**  
`Clean` untuk data valid, `Error` untuk data yang masih bermasalah.

**Kapan checkbox Patch Issue dipakai?**  
Saat kendala ada pada aplikasi entry, sementara data lapangan sudah benar.

**Apakah data entry bisa dihapus?**  
Bisa, tetapi hanya untuk entry milik akun Anda.

## Dukungan
Jika ada kendala operasional, hubungi admin aplikasi atau tim pengelola MONIKA di unit Anda.

---
Versi dokumen: 1.1  
Terakhir diperbarui: 15 Februari 2026  
