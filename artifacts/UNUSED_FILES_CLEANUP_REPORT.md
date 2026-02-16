# Laporan Cleanup File Tidak Terpakai

Tanggal: 2026-02-16
Proyek: MONIKA (`e:\laragon\www\monika`)

## Metodologi Analisis

Analisis dilakukan dengan kombinasi:
1. Pemetaan entrypoint framework CodeIgniter:
   - Route aktif dari `app/Config/Routes.php`
   - Pemanggilan view dari controller (`view('...')`)
   - Relasi antar-view (`$this->extend(...)`, `$this->include(...)`)
2. Pengecekan dependensi antar modul:
   - Referensi class model/controller di `app/`, `tests/`, `scripts/`
   - Pencarian referensi literal nama file/script lintas repo
3. Validasi aman sebelum hapus:
   - Pastikan file tidak menjadi dependency modul lain
   - Untuk `Home.php`/`welcome_message.php`, route `welcome` dihapus terlebih dahulu

## File Terkonfirmasi Tidak Terpakai dan Dihapus

1. `app/Models/RoleModel.php`
- Alasan: Tidak ada referensi runtime di `app/`, `tests/`, `scripts/` (hanya deklarasi file sendiri + referensi dokumentasi).

2. `app/Models/UserModel.php`
- Alasan: Tidak ada referensi runtime di `app/`, `tests/`, `scripts/` (hanya deklarasi file sendiri + referensi dokumentasi).

3. `app/Views/layout/footer.php`
- Alasan: Tidak pernah dipanggil oleh `layout/template.php` maupun view lain (`include/extend` tidak ditemukan).

4. `app/Controllers/Home.php`
- Alasan: Controller demo; setelah route `welcome` dilepas, tidak ada route/modul yang bergantung.

5. `app/Views/welcome_message.php`
- Alasan: Hanya dipakai oleh `Home::index`; setelah `Home.php` dihapus, file ini orphan.

6. `scripts/tmp-sidebar-compare.mjs`
- Alasan: Script temporary, tidak direferensikan workflow aplikasi/test utama.

7. `tests/sidebar-compare.spec.ts`
- Alasan: Tidak termasuk test runner utama (`phpunit`), tidak direferensikan modul lain.

## Perubahan Konfigurasi Terkait

- `app/Config/Routes.php`
  - Menghapus route: `$routes->get('welcome', 'Home::index');`

## Sinkronisasi Dokumentasi

- `docs/PROJECT_STRUCTURE.md`
  - Menghapus entri `RoleModel.php` dan `UserModel.php`.
  - Menambahkan entri `LaporanModel.php` dan `LoginAttemptModel.php`.
- `docs/FEATURES_LIST.md`
  - Menghapus mapping `roles -> RoleModel` dan `users -> UserModel`.
  - Menambahkan mapping `login_attempts -> LoginAttemptModel`.

## Validasi Pasca Penghapusan

1. Validasi referensi:
- Tidak ada lagi referensi runtime ke `RoleModel`, `UserModel`, `Home::index` di `app/tests/scripts`.
- File terhapus dipastikan sudah tidak ada di filesystem.

2. Validasi sintaks:
- `php -l app/Config/Routes.php` => valid.
- `php -l app/Controllers/Auth.php` => valid.

3. Validasi route:
- `php spark routes` sukses.
- Route inti aplikasi tetap tersedia (auth, dashboard, dokumen, presensi, kartu-kendali, kegiatan, laporan, monitoring, uji-petik).

4. Pengujian menyeluruh:
- `vendor/bin/phpunit` dijalankan.
- Hasil: test suite gagal karena isu migration SQLite yang sudah ada sebelumnya (contoh error: `near "SHOW": syntax error` pada migration `2026-02-15-151500_CreatePresensiTable.php`).
- Tidak ditemukan indikasi error yang berasal dari file-file yang dihapus.

## Ringkasan

Cleanup file tidak terpakai telah dilakukan secara aman dengan pemeriksaan referensi/dependensi sebelum hapus dan verifikasi pasca-hapus. Perubahan bersifat non-fungsional terhadap alur utama aplikasi.
