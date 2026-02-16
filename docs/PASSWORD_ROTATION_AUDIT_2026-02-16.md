# Audit & Rotasi Password MONIKA

**Tanggal audit:** 16 Februari 2026  
**Target password baru:** `Monika@2026!`

## Ruang Lingkup Audit

Audit dilakukan pada:
- User account aplikasi (seeder, dump SQL, fixture test)
- Service account aplikasi (database credential di file environment/template)
- Dokumentasi operasional kredensial

## Hasil Perubahan

### 1. User account aplikasi
- `app/Database/Seeds/AdminSeeder.php`
- `app/Database/Seeds/UserSeeder.php`
- `app/Database/Seeds/UserDummySeeder.php` (sudah konsisten sebelumnya)
- `fix_schema.sql`
- `monika.sql`
- `tests/feature/AuthTest.php`

Semua akun default/seeded disetarakan ke password `Monika@2026!`.

### 2. Service account (environment variables)
- `.env`
- `env`
- `phpunit.xml.dist`
- `app/Config/Database.php` (sample password di komentar)

Nilai password service di template konfigurasi disetarakan ke `Monika@2026!`.

### 3. Dokumentasi
- `docs/KARTU_KENDALI_QUICKSTART.md`

Panduan login dan contoh konfigurasi database telah diperbarui ke password baru.

### 4. Otomasi audit dan rotasi
- `scripts/audit-password-rotation.ps1`
  - Validasi tidak ada referensi password lama di codebase
  - Validasi file wajib sudah memakai `Monika@2026!`
- `scripts/sql/rotate_all_passwords_monika_2026.sql`
  - Rotasi massal password seluruh akun tabel `users`
  - Query verifikasi total akun yang sudah memakai hash baru

## Verifikasi yang Harus Dijalankan Per Environment

1. Jalankan SQL rotasi akun user aplikasi:
   - `scripts/sql/rotate_all_passwords_monika_2026.sql`
2. Jalankan audit codebase:
   - `pwsh ./scripts/audit-password-rotation.ps1`
3. Jalankan test autentikasi:
   - `php vendor/bin/phpunit tests/feature/AuthTest.php`

## Eksekusi Validasi (Development Lokal)

Perintah yang sudah dieksekusi dan hasilnya:
- `pwsh ./scripts/audit-password-rotation.ps1` → **PASS**
- `php vendor/bin/phpunit --no-coverage tests/feature/AuthTest.php` → **PASS** (3 tests, 10 assertions)
- `php vendor/bin/phpunit --no-coverage` → **PASS** (8 tests, 17 assertions)
- Rotasi service account DB lokal:
  - `ALTER USER 'root'@'localhost' IDENTIFIED BY 'Monika@2026!';` → **PASS**
  - Validasi login DB dengan password baru → **PASS**
- `php spark db:seed UserSeeder` (dengan `.env` password baru) → **PASS**
- `php spark migrate:status` (dengan `.env` password baru) → **PASS**

## Catatan Penting (Staging & Production)

Rotasi **akun sistem/database server** (contoh `root`, `monika_app`) harus dijalankan langsung di server database masing-masing environment menggunakan `ALTER USER ... IDENTIFIED BY 'Monika@2026!';`.  
Repository ini hanya dapat menjamin konsistensi konfigurasi dan akun aplikasi yang dikelola dari codebase.
