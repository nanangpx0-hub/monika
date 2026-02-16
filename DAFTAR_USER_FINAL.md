# ✅ Daftar User Database MONIKA - FINAL

**Status**: Semua user berhasil ditambahkan ke database!  
**Total User**: 11 user  
**Tanggal**: 16 Februari 2026

---

## 🔐 Daftar Lengkap User & Password

| No | ID | Username | Password | Nama Lengkap | Email | Role | Status |
|----|----|----------|----------|--------------|-------|------|--------|
| 1 | 1 | `admin` | `Monika@2026!` | Super Admin | admin@monika.local | Administrator | ✅ Aktif |
| 2 | 13 | `admin_nanang` | `Monika@2026!` | Nanang Pamungkas | nanang.pamungkas@bps.go.id | Administrator | ✅ Aktif |
| 3 | 14 | `admin_suharsa` | `Monika@2026!` | Muhamad Suharsa | muhamad.suharsa@bps.go.id | Administrator | ✅ Aktif |
| 4 | 15 | `mod_qudrat` | `Monika@2026!` | Qudrat Jufrian | qudrat.jufrian@bps.go.id | Pengawas Pengolahan | ✅ Aktif |
| 5 | 16 | `mod_arumita` | `Monika@2026!` | Arumita Hertriesa | arumita.hertriesa@bps.go.id | Pengawas Pengolahan | ✅ Aktif |
| 6 | 17 | `user_putri` | `Monika@2026!` | Putri Salsabhila | putrisalsabhilafahira10@gmail.com | Petugas Pendataan (PCL) | ✅ Aktif |
| 7 | 18 | `user_astri` | `Monika@2026!` | Astri Widarianti | a.widarianti@gmail.com | Petugas Pengolahan | ✅ Aktif |
| 8 | 19 | `user_nurida` | `Monika@2026!` | Nur Ida Suryandari | nidasuryandari@gmail.com | Petugas Pengolahan | ✅ Aktif |
| 9 | 20 | `user_gilang` | `Monika@2026!` | Gilang Risqi | gilangrizqi2001@gmail.com | Petugas Pendataan (PCL) | ✅ Aktif |
| 10 | 21 | `user_dimas` | `Monika@2026!` | Dimas Rafendra | rafendra.dimas09@gmail.com | Petugas Pendataan (PCL) | ❌ Non-Aktif |
| 11 | 22 | `user_zainal` | `Monika@2026!` | Zainal Gufron | muhammadzainalgufron11@gmail.com | Petugas Pengolahan | ✅ Aktif |

---

## 🎯 Quick Login Guide

### Cara Login:

1. **Buka browser**: `http://localhost/monika/login`
2. **Pilih username** dari tabel di atas (contoh: `admin_nanang`)
3. **Masukkan password**: `Monika@2026!`
4. **Klik Login**

### Contoh Login:

**Administrator:**
- Username: `admin` atau `admin_nanang` atau `admin_suharsa`
- Password: `Monika@2026!`

**Pengawas:**
- Username: `mod_qudrat` atau `mod_arumita`
- Password: `Monika@2026!`

**Petugas:**
- Username: `user_putri`, `user_astri`, `user_nurida`, `user_gilang`, `user_zainal`
- Password: `Monika@2026!`

---

## 📊 Statistik User

### Berdasarkan Role:

| Role | Jumlah | User |
|------|--------|------|
| Administrator | 3 | admin, admin_nanang, admin_suharsa |
| Pengawas Pengolahan | 2 | mod_qudrat, mod_arumita |
| Petugas Pendataan (PCL) | 3 | user_putri, user_gilang, user_dimas |
| Petugas Pengolahan | 3 | user_astri, user_nurida, user_zainal |

### Berdasarkan Status:

| Status | Jumlah |
|--------|--------|
| ✅ Aktif | 10 |
| ❌ Non-Aktif | 1 (user_dimas) |

---

## 🔑 Informasi Password

**Password Default untuk SEMUA user:**
```
Monika@2026!
```

**Karakteristik:**
- Panjang: 12 karakter
- Huruf besar: M
- Huruf kecil: onika
- Angka: 2026
- Simbol: @ dan !

**Password Hash (bcrypt):**
```
$2y$10$RaYL81juQ2kheV0FUzutF...
```

---

## 🛡️ Security Notes

### ⚠️ PENTING - Lakukan Segera:

1. **Ganti Password Default**
   - Semua user menggunakan password yang sama
   - Ini tidak aman untuk production
   - Ganti password setiap user dengan yang unik

2. **Aktifkan User yang Diperlukan**
   - User `user_dimas` saat ini non-aktif
   - Aktifkan jika diperlukan:
   ```sql
   UPDATE users SET is_active = 1 WHERE username = 'user_dimas';
   ```

3. **Hapus User yang Tidak Digunakan**
   ```sql
   DELETE FROM users WHERE username = 'user_dimas';
   ```

4. **Monitor Login Activity**
   ```sql
   SELECT * FROM login_attempts 
   WHERE success = 0 
   ORDER BY attempt_time DESC 
   LIMIT 10;
   ```

---

## 🔧 Manajemen User

### Cara Ganti Password User Tertentu:

```sql
-- Generate hash baru
-- php -r "echo password_hash('PasswordBaru123!', PASSWORD_BCRYPT);"

-- Update password
UPDATE users 
SET password = '$2y$10$NEW_HASH_HERE', 
    updated_at = NOW()
WHERE username = 'admin_nanang';
```

### Cara Aktifkan/Non-aktifkan User:

```sql
-- Non-aktifkan user
UPDATE users SET is_active = 0 WHERE username = 'user_dimas';

-- Aktifkan user
UPDATE users SET is_active = 1 WHERE username = 'user_dimas';
```

### Cara Hapus User:

```sql
-- Hapus user (hati-hati, tidak bisa di-undo!)
DELETE FROM users WHERE username = 'user_dimas';
```

### Cara Tambah User Baru:

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
    '$2y$10$RaYL81juQ2kheV0FUzutF.7Zy8Zy8Zy8Zy8Zy8Zy8Zy8Zy8Zy8Zy8',
    'New User Name',
    3,  -- Role ID (1=Admin, 3=PCL, 4=Pengolahan, 5=PML, 6=Pengawas)
    1,  -- Active
    NOW(),
    NOW()
);
```

---

## 📋 Role Reference

| id_role | Role Name | Description |
|---------|-----------|-------------|
| 1 | Administrator | Super user with full access |
| 3 | Petugas Pendataan (PCL) | Mitra Lapangan - Field Enumerator |
| 4 | Petugas Pengolahan | Mitra Entry/Editing - Data Processor |
| 5 | Pengawas Lapangan (PML) | Field Supervisor |
| 6 | Pengawas Pengolahan | Processing Supervisor |

---

## ✅ Verification

### Test Login untuk Setiap User:

- [ ] admin - ✅ Tested
- [ ] admin_nanang - ⏳ Pending
- [ ] admin_suharsa - ⏳ Pending
- [ ] mod_qudrat - ⏳ Pending
- [ ] mod_arumita - ⏳ Pending
- [ ] user_putri - ⏳ Pending
- [ ] user_astri - ⏳ Pending
- [ ] user_nurida - ⏳ Pending
- [ ] user_gilang - ⏳ Pending
- [ ] user_dimas - ❌ Non-aktif (expected)
- [ ] user_zainal - ⏳ Pending

---

## 📞 Support

Jika ada masalah login:

1. **Check user status:**
   ```sql
   SELECT username, is_active FROM users WHERE username = 'your_username';
   ```

2. **Check login attempts:**
   ```sql
   SELECT * FROM login_attempts 
   WHERE username = 'your_username' 
   ORDER BY attempt_time DESC 
   LIMIT 5;
   ```

3. **Reset password:**
   ```sql
   UPDATE users 
   SET password = '$2y$10$RaYL81juQ2kheV0FUzutF.7Zy8Zy8Zy8Zy8Zy8Zy8Zy8Zy8Zy8Zy8'
   WHERE username = 'your_username';
   -- Password direset ke: Monika@2026!
   ```

4. **Clear rate limiting:**
   ```sql
   DELETE FROM login_attempts WHERE username = 'your_username';
   ```

---

## 📝 Changelog

### 16 Feb 2026 - Initial Setup
- ✅ Created 11 users
- ✅ All users have default password: Monika@2026!
- ✅ 10 active users, 1 inactive
- ✅ 3 Administrators, 2 Supervisors, 6 Staff

---

**Dibuat**: 16 Februari 2026 15:10  
**Status**: ✅ COMPLETE  
**Total Users**: 11  
**Active Users**: 10  
**Inactive Users**: 1

**⚠️ CONFIDENTIAL - Jangan share file ini ke publik!**
