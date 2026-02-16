# Daftar User dan Password - Database MONIKA

## 🔐 Informasi Akses

**PENTING**: Dokumen ini berisi informasi sensitif. Jangan share ke publik!

---

## 📊 User yang Ada di Database Saat Ini

### User Aktif:

| No | Username | Email | Password | Role | Status |
|----|----------|-------|----------|------|--------|
| 1 | `admin` | admin@monika.local | `Monika@2026!` | Super Admin (id_role: 1) | ✅ Aktif |

---

## 👥 User yang Tersedia di Seeder (Belum di Database)

Jalankan seeder untuk menambahkan user-user berikut:

```bash
php spark db:seed UserDummySeeder
```

### Daftar User dari Seeder:

| No | Nama Lengkap | Username | Email | Password | Role | Status |
|----|--------------|----------|-------|----------|------|--------|
| 1 | Nanang Pamungkas | `admin_nanang` | nanang.pamungkas@bps.go.id | `Monika@2026!` | Administrator (1) | Aktif |
| 2 | Muhamad Suharsa | `admin_suharsa` | muhamad.suharsa@bps.go.id | `Monika@2026!` | Administrator (1) | Aktif |
| 3 | Qudrat Jufrian | `mod_qudrat` | qudrat.jufrian@bps.go.id | `Monika@2026!` | Pengawas Pengolahan (6) | Aktif |
| 4 | Arumita Hertriesa | `mod_arumita` | arumita.hertriesa@bps.go.id | `Monika@2026!` | Pengawas Pengolahan (6) | Aktif |
| 5 | Putri Salsabhila | `user_putri` | putrisalsabhilafahira10@gmail.com | `Monika@2026!` | Petugas Pendataan/PCL (3) | Aktif |
| 6 | Astri Widarianti | `user_astri` | a.widarianti@gmail.com | `Monika@2026!` | Petugas Pengolahan (4) | Aktif |
| 7 | Nur Ida Suryandari | `user_nurida` | nidasuryandari@gmail.com | `Monika@2026!` | Petugas Pengolahan (4) | Aktif |
| 8 | Gilang Risqi | `user_gilang` | gilangrizqi2001@gmail.com | `Monika@2026!` | Petugas Pendataan/PCL (3) | Aktif |
| 9 | Dimas Rafendra | `user_dimas` | rafendra.dimas09@gmail.com | `Monika@2026!` | Petugas Pendataan/PCL (3) | Non-Aktif |
| 10 | Zainal Gufron | `user_zainal` | muhammadzainalgufron11@gmail.com | `Monika@2026!` | Petugas Pengolahan (4) | Aktif |

---

## 🔑 Password Default

**Semua user menggunakan password yang sama:**

```
Password: Monika@2026!
```

**Format Password:**
- Huruf besar: M
- Huruf kecil: onika
- Simbol: @
- Angka: 2026
- Simbol: !

**Password Hash (bcrypt):**
```
$2y$10$RaYL81juQ2kheV0FUzutF...
```

---

## 📝 Cara Login

### Login dengan User yang Ada:

1. Buka: `http://localhost/monika/login`
2. Masukkan username: `admin`
3. Masukkan password: `Monika@2026!`
4. Klik Login

### Login dengan User dari Seeder:

**Langkah 1: Jalankan Seeder**
```bash
php spark db:seed UserDummySeeder
```

**Langkah 2: Login**
1. Buka: `http://localhost/monika/login`
2. Pilih salah satu username dari tabel di atas
3. Masukkan password: `Monika@2026!`
4. Klik Login

---

## 🔧 Cara Menambah User Baru

### Via Seeder (Recommended):

```bash
# Tambah semua user dummy
php spark db:seed UserDummySeeder

# Atau tambah admin saja
php spark db:seed AdminSeeder
```

### Via SQL Manual:

```sql
INSERT INTO users (
    username, 
    email, 
    password, 
    fullname, 
    id_role, 
    is_active, 
    created_at, 
    updated_at
) VALUES (
    'newuser',
    'newuser@example.com',
    '$2y$10$RaYL81juQ2kheV0FUzutF.7Zy8Zy8Zy8Zy8Zy8Zy8Zy8Zy8Zy8Zy8',  -- Password: Monika@2026!
    'New User',
    3,  -- Role: User
    1,  -- Active
    NOW(),
    NOW()
);
```

### Via PHP Script:

```php
<?php
$passwordHash = password_hash('Monika@2026!', PASSWORD_BCRYPT);

$data = [
    'username'   => 'newuser',
    'email'      => 'newuser@example.com',
    'password'   => $passwordHash,
    'fullname'   => 'New User',
    'id_role'    => 3,
    'is_active'  => 1,
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s'),
];

$db->table('users')->insert($data);
```

---

## 🔐 Cara Ganti Password

### Via SQL:

```sql
-- Ganti password untuk user tertentu
UPDATE users 
SET password = '$2y$10$NEW_HASH_HERE', 
    updated_at = NOW()
WHERE username = 'admin';
```

### Generate Password Hash Baru:

```php
<?php
// Generate hash untuk password baru
$newPassword = 'PasswordBaru123!';
$hash = password_hash($newPassword, PASSWORD_BCRYPT);
echo $hash;
```

Atau via command line:
```bash
php -r "echo password_hash('PasswordBaru123!', PASSWORD_BCRYPT);"
```

---

## 🛡️ Security Best Practices

### ⚠️ PENTING - Lakukan Setelah Setup:

1. **Ganti Password Default**
   ```sql
   UPDATE users 
   SET password = '$2y$10$NEW_HASH', 
       updated_at = NOW()
   WHERE username = 'admin';
   ```

2. **Hapus User yang Tidak Digunakan**
   ```sql
   DELETE FROM users WHERE username = 'user_dimas';  -- User non-aktif
   ```

3. **Aktifkan 2FA** (jika sudah diimplementasikan)

4. **Monitor Login Attempts**
   ```sql
   SELECT * FROM login_attempts 
   WHERE success = 0 
   ORDER BY attempt_time DESC 
   LIMIT 10;
   ```

5. **Regular Password Update**
   - Ganti password setiap 3 bulan
   - Gunakan password yang kuat
   - Jangan gunakan password yang sama

---

## 📊 Role Mapping

| id_role | Role Name | Description |
|---------|-----------|-------------|
| 1 | Administrator | Super user with full access |
| 3 | Petugas Pendataan (PCL) | Mitra Lapangan - Field Enumerator |
| 4 | Petugas Pengolahan | Mitra Entry/Editing - Data Processor |
| 5 | Pengawas Lapangan (PML) | Field Supervisor |
| 6 | Pengawas Pengolahan | Processing Supervisor |

---

## 🔍 Cara Cek User di Database

### Via MySQL Command:

```bash
mysql -u root -pMonika@2026! monika -e "SELECT id_user, username, email, fullname, id_role, is_active FROM users"
```

### Via PHP Spark:

```bash
php spark db:table users
```

### Via SQL Query:

```sql
-- Lihat semua user
SELECT 
    id_user,
    username,
    email,
    fullname,
    id_role,
    is_active,
    created_at
FROM users
ORDER BY id_user;

-- Lihat user aktif saja
SELECT * FROM users WHERE is_active = 1;

-- Lihat admin saja
SELECT * FROM users WHERE id_role = 1;
```

---

## 🆘 Troubleshooting

### Lupa Password?

**Solusi 1: Reset via SQL**
```sql
UPDATE users 
SET password = '$2y$10$RaYL81juQ2kheV0FUzutF.7Zy8Zy8Zy8Zy8Zy8Zy8Zy8Zy8Zy8Zy8'
WHERE username = 'admin';
-- Password direset ke: Monika@2026!
```

**Solusi 2: Buat User Emergency**
```sql
INSERT INTO users (username, password, email, fullname, id_role, is_active, created_at, updated_at)
VALUES (
    'emergency',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'emergency@monika.local',
    'Emergency User',
    1,
    1,
    NOW(),
    NOW()
);
-- Login dengan: emergency / password
```

### User Tidak Bisa Login?

1. **Cek status aktif:**
   ```sql
   SELECT username, is_active FROM users WHERE username = 'admin';
   ```

2. **Aktifkan user:**
   ```sql
   UPDATE users SET is_active = 1 WHERE username = 'admin';
   ```

3. **Cek password hash:**
   ```sql
   SELECT username, LEFT(password, 20) as pwd FROM users WHERE username = 'admin';
   ```
   Hash harus dimulai dengan `$2y$10$`

4. **Clear login attempts:**
   ```sql
   DELETE FROM login_attempts WHERE username = 'admin';
   ```

---

## 📞 Support

Jika masih ada masalah:

1. Check [TROUBLESHOOTING_LOGIN.md](TROUBLESHOOTING_LOGIN.md)
2. Run diagnostic: `php spark diagnose:login`
3. Check logs: `writable/logs/`
4. Contact admin

---

## ⚠️ SECURITY WARNING

**JANGAN:**
- ❌ Share password ke orang lain
- ❌ Gunakan password default di production
- ❌ Commit file ini ke public repository
- ❌ Share screenshot yang berisi password
- ❌ Simpan password di plain text

**LAKUKAN:**
- ✅ Ganti password default setelah setup
- ✅ Gunakan password manager
- ✅ Enable 2FA jika tersedia
- ✅ Monitor login attempts
- ✅ Regular security audit

---

**Dibuat**: 16 Februari 2026  
**Status**: 🔒 CONFIDENTIAL  
**Akses**: Admin Only

**CATATAN**: Hapus atau amankan file ini setelah setup selesai!
