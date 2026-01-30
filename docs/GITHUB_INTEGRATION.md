# Panduan Integrasi Dokumentasi ke GitHub

Dokumen ini menjelaskan cara mengatur dan menggunakan sistem sinkronisasi otomatis untuk dokumentasi proyek MONIKA ke repository GitHub pribadi.

## 1. Persiapan

Sebelum memulai, pastikan Anda memiliki:
*   **Git** terinstal di sistem Anda.
*   Akun **GitHub**.
*   Akses ke repository target: `https://github.com/nanangpx0-hub/monika.git`

## 2. Konfigurasi Autentikasi (Personal Access Token)

Karena GitHub tidak lagi mendukung autentikasi password untuk HTTPS, Anda perlu membuat **Personal Access Token (PAT)**.

1.  Login ke GitHub.
2.  Masuk ke **Settings** > **Developer settings** > **Personal access tokens** > **Tokens (classic)**.
3.  Klik **Generate new token (classic)**.
4.  Berikan nama (Note), misal: `Monika Docs Sync`.
5.  Pilih scope/izin berikut:
    *   `repo` (Full control of private repositories)
    *   `workflow` (Opsional, jika ingin mengedit action)
6.  Klik **Generate token**.
7.  **PENTING:** Salin token tersebut dan simpan di tempat aman. Anda tidak akan bisa melihatnya lagi.

## 3. Setup Koneksi Lokal

Buka terminal (PowerShell atau Command Prompt) di folder proyek `e:\laragon\www\monika`, lalu jalankan perintah berikut untuk mengatur remote URL menggunakan token Anda:

```bash
# Format: git remote set-url origin https://<USERNAME>:<TOKEN>@github.com/nanangpx0-hub/monika.git

# Contoh:
git remote set-url origin https://nanangpx0-hub:ghp_AbC123...@github.com/nanangpx0-hub/monika.git
```

> **Catatan Keamanan:** Token Anda akan tersimpan dalam konfigurasi git lokal `.git/config`. Pastikan komputer Anda aman.

## 4. Menggunakan Script Otomatis

Telah disediakan script PowerShell untuk mempermudah sinkronisasi dokumentasi.

File: `scripts/sync_docs.ps1`

### Cara Penggunaan

1.  **Cek Status Koneksi**
    Jalankan script untuk memvalidasi koneksi ke GitHub.
    ```powershell
    ./scripts/sync_docs.ps1 -CheckOnly
    ```

2.  **Sinkronisasi Manual (Sekali Jalan)**
    Untuk melakukan push semua perubahan dokumentasi (`.md`) saat ini.
    ```powershell
    ./scripts/sync_docs.ps1 -Sync
    ```

3.  **Mode Monitoring (Watch Mode)**
    Script akan terus berjalan dan memantau perubahan pada file `.md` setiap 30 detik. Jika ada perubahan, akan otomatis di-commit dan push.
    ```powershell
    ./scripts/sync_docs.ps1 -Watch
    ```

## 5. Troubleshooting

### Masalah Umum

*   **Error: "Authentication failed"**
    *   Cek kembali apakah Personal Access Token (PAT) Anda masih valid dan belum expired.
    *   Ulangi langkah "Setup Koneksi Lokal" dengan token baru.

*   **Error: "fast-forward" / Conflict**
    *   Ini terjadi jika ada perubahan di GitHub yang belum ada di lokal.
    *   Solusi: Jalankan `git pull origin main` (atau branch yang sesuai) terlebih dahulu manual di terminal untuk menyelesaikan konflik.

*   **Script tidak bisa dijalankan**
    *   Jika muncul error "execution of scripts is disabled on this system", jalankan perintah ini di PowerShell sebagai Administrator:
        ```powershell
        Set-ExecutionPolicy RemoteSigned
        ```
