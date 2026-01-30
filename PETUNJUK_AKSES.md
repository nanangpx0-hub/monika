# Petunjuk Akses Sistem MONIKA

Berikut adalah panduan **resmi dan termudah** untuk menjalankan dan mengakses aplikasi MONIKA.

## 1. Persiapan Awal

Pastikan Anda sudah berada di folder proyek (biasanya `c:\laragon\www\monika`).

## 2. Menjalankan Server

Karena kendala konfigurasi pada Laragon/Apache, kita menggunakan **Spark Server** bawaan aplikasi yang lebih stabil.

1. Buka Terminal (Bisa via VS Code, PowerShell, atau CMD).
2. Pastikan Anda berada di direktori project.
3. Jalankan perintah berikut:

    ```bash
    php spark serve
    ```

    *(Jangan tutup terminal ini selama aplikasi digunakan)*.

## 3. Login ke Aplikasi

Setelah server berjalan (biasanya muncul pesan `started on http://localhost:8080`), ikuti langkah ini:

1. Buka Browser (Chrome / Edge).
2. Akses URL: **[http://localhost:8080/login](http://localhost:8080/login)**
3. Masukkan Kredensial Administrator:
    - **Username:** `admin`
    - **Password:** `admin`

## 4. Troubleshooting

- **Jika Port 8080 terpakai**: Jalankan `php spark serve --port 8081`, lalu akses `http://localhost:8081`.
- **Jika Lupa Password**: Password default user lain (PCL/Pengawas) adalah `password123`.

---
*Catatan: Jangan gunakan URL lama (`localhost/monika/...`) karena akan menyebabkan error 404.*
