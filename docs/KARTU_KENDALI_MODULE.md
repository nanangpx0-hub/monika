# Modul Kartu Kendali Digital - MONIKA

## Deskripsi
Modul Kartu Kendali Digital adalah fitur inti aplikasi MONIKA yang memungkinkan Petugas Pengolahan untuk melaporkan hasil entry data Susenas per Ruta (Nomor Urut 1-10).

## Fitur Utama

### 1. Daftar NKS (Index)
- Menampilkan semua NKS yang sudah diterima dokumennya
- Progress bar untuk setiap NKS
- Informasi jumlah ruta yang sudah selesai vs diterima
- Tombol akses ke detail entry per NKS

### 2. Detail Entry (Grid 10 Ruta)
- Grid visual 10 kotak untuk ruta 1-10
- Color-coded status:
  - **Abu-abu**: Belum diterima dari logistik (LOCKED_LOGISTIC)
  - **Kuning**: Sedang dikerjakan petugas lain (LOCKED_USER)
  - **Hijau**: Selesai dengan status Clean
  - **Merah**: Selesai dengan status Error
  - **Putih**: Siap dikerjakan (OPEN)

### 3. Entry Data
- Modal form untuk input status entry
- Pilihan status: Clean atau Error
- Checkbox untuk Patch Issue (masalah aplikasi)
- Validasi untuk mencegah konflik data

### 4. Edit/Hapus Data
- Petugas dapat mengedit entry miliknya sendiri
- Tombol hapus untuk membatalkan entry
- Konfirmasi sebelum menghapus

## Struktur Database

### Tabel: kartu_kendali
```sql
CREATE TABLE `kartu_kendali` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `nks` VARCHAR(10) NOT NULL,
  `no_ruta` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `status_entry` ENUM('Clean', 'Error') DEFAULT 'Clean',
  `is_patch_issue` TINYINT(1) DEFAULT 0,
  `tgl_entry` DATE NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  UNIQUE KEY `unique_nks_ruta` (`nks`, `no_ruta`),
  FOREIGN KEY (`nks`) REFERENCES `nks_master`(`nks`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id_user`) ON DELETE CASCADE
);
```

## Logika Bisnis

### Status Ruta
1. **LOCKED_LOGISTIC**: Dokumen fisik belum diterima
   - Kondisi: `no_ruta` > `jml_ruta_terima` dari tabel `tanda_terima`
   - Aksi: Tombol disabled, tidak bisa dikerjakan

2. **LOCKED_USER**: Sudah dikerjakan petugas lain
   - Kondisi: Entry sudah ada DAN `user_id` != user login
   - Aksi: Tombol disabled, tampilkan nama petugas

3. **DONE**: Sudah dikerjakan oleh user login
   - Kondisi: Entry sudah ada DAN `user_id` == user login
   - Aksi: Bisa edit atau hapus

4. **OPEN**: Siap dikerjakan
   - Kondisi: Dokumen sudah diterima DAN belum ada yang mengerjakan
   - Aksi: Bisa klik untuk entry data

### Validasi
- Satu ruta hanya bisa dikerjakan oleh satu petugas
- User tidak bisa menimpa data petugas lain
- Unique constraint pada kombinasi `nks` + `no_ruta`

## Routes

```php
$routes->group('kartu-kendali', static function ($routes): void {
    $routes->get('/', 'KartuKendali::index');
    $routes->get('detail/(:segment)', 'KartuKendali::detail/$1');
    $routes->post('store', 'KartuKendali::store', ['filter' => 'csrf']);
    $routes->post('delete', 'KartuKendali::delete', ['filter' => 'csrf']);
});
```

## API Endpoints

### GET /kartu-kendali
Menampilkan daftar NKS dengan progress

### GET /kartu-kendali/detail/{nks}
Menampilkan grid 10 ruta untuk NKS tertentu

### POST /kartu-kendali/store
Menyimpan atau update entry data
**Parameters:**
- `nks`: Kode NKS
- `no_ruta`: Nomor ruta (1-10)
- `status_entry`: Clean atau Error
- `is_patch_issue`: 0 atau 1

**Response:**
```json
{
  "status": "success",
  "message": "Data berhasil disimpan."
}
```

### POST /kartu-kendali/delete
Menghapus entry data milik user login
**Parameters:**
- `nks`: Kode NKS
- `no_ruta`: Nomor ruta

**Response:**
```json
{
  "status": "success",
  "message": "Data berhasil dihapus."
}
```

## Testing

### Menjalankan Seeder
```bash
php spark db:seed KartuKendaliTestSeeder
```

Seeder akan membuat:
- 3 NKS sample (26001, 26002, 26003)
- Data tanda terima dengan jumlah ruta berbeda
- NKS 26001: 10 ruta diterima (semua bisa dikerjakan)
- NKS 26002: 6 ruta diterima (4 terkunci)
- NKS 26003: 4 ruta diterima (6 terkunci)

### Skenario Testing
1. Login sebagai petugas pengolahan
2. Akses menu Kartu Kendali
3. Pilih salah satu NKS
4. Klik ruta yang berwarna putih (OPEN)
5. Isi form entry (pilih Clean/Error)
6. Simpan data
7. Verifikasi kotak berubah warna (hijau/merah)
8. Coba edit data yang sudah disimpan
9. Coba hapus data

## Dependencies
- CodeIgniter 4.7+
- AdminLTE 3.2.0
- jQuery 3.7.1
- Bootstrap 4.6.2
- DataTables 1.11.5
- SweetAlert2 11
- Font Awesome 6.5.2

## Security
- CSRF protection pada semua POST request
- Auth filter untuk semua route
- Validasi ownership sebelum edit/delete
- Prepared statements untuk query database
- XSS protection dengan `esc()` helper

## Performance
- Index query menggunakan JOIN untuk efisiensi
- Unique constraint untuk prevent duplicate
- Foreign key dengan CASCADE untuk data integrity
- DataTables untuk pagination client-side

## Future Enhancements
- Export data ke Excel
- Statistik per petugas
- Notifikasi real-time
- Bulk entry untuk multiple ruta
- History log perubahan data
- Filter by tanggal entry
- Search functionality
