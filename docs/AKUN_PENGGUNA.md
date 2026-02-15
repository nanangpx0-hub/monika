# Daftar Akun Pengguna Dummy - Aplikasi MONIKA

## Informasi Login Default
- Password default seluruh akun: `Monika@2026!`
- URL login lokal: `http://localhost/MONIKA/login`

## Tabel Kredensial
| No | Nama Lengkap          | Username      | Email                                   | Role      | Status    |
|----|-----------------------|---------------|-----------------------------------------|-----------|-----------|
| 1  | Nanang Pamungkas      | admin_nanang  | nanang.pamungkas@bps.go.id              | Admin     | Aktif     |
| 2  | Muhamad Suharsa       | admin_suharsa | muhamad.suharsa@bps.go.id               | Admin     | Aktif     |
| 3  | Qudrat Jufrian        | mod_qudrat    | qudrat.jufrian@bps.go.id                | Moderator | Aktif     |
| 4  | Arumita Hertriesa     | mod_arumita   | arumita.hertriesa@bps.go.id             | Moderator | Aktif     |
| 5  | Putri Salsabhila      | user_putri    | putrisalsabhilafahira10@gmail.com       | User      | Aktif     |
| 6  | Astri Widarianti      | user_astri    | a.widarianti@gmail.com                  | User      | Aktif     |
| 7  | Nur Ida Suryandari    | user_nurida   | nidasuryandari@gmail.com                | User      | Aktif     |
| 8  | Gilang Risqi          | user_gilang   | gilangrizqi2001@gmail.com               | User      | Aktif     |
| 9  | Dimas Rafendra        | user_dimas    | rafendra.dimas09@gmail.com              | User      | Non-Aktif |
| 10 | Zainal Gufron         | user_zainal   | muhammadzainalgufron11@gmail.com        | User      | Aktif     |

## Cara Install Data
Jalankan perintah berikut untuk melakukan seeding ulang data dummy pengguna:

```bash
php spark db:seed UserDummySeeder
```

## Catatan Penting
Data akun pada dokumen ini hanya untuk lingkungan **Development** dan tidak boleh digunakan di **Production**.
