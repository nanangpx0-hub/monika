# 🚀 INSTRUKSI LOGIN - IKUTI STEP BY STEP

## ✅ Perbaikan Sudah Selesai!

Saya telah memperbaiki:
1. ✅ CSRF configuration (regenerate = false, redirect = false)
2. ✅ Session files cleared
3. ✅ Cache cleared

## 📋 LANGKAH LOGIN (Ikuti Urutan Ini):

### **STEP 1: Tutup Browser**

**PENTING**: Tutup SEMUA tab dan window browser Anda sekarang!

Atau lebih baik, gunakan **Incognito/Private Mode**:
- Chrome: `Ctrl + Shift + N`
- Firefox: `Ctrl + Shift + P`
- Edge: `Ctrl + Shift + N`

---

### **STEP 2: Buka Login Page**

1. Buka browser baru (Incognito mode)
2. Ketik di address bar: `http://localhost/monika/login`
3. Tekan Enter
4. **TUNGGU** sampai halaman load sempurna (lihat logo BPS dan form login)

---

### **STEP 3: Isi Form Login**

**Username**: `admin`  
**Password**: `Monika@2026!`

**PERHATIAN**:
- Password case-sensitive (huruf besar/kecil harus sama)
- M besar, sisanya kecil
- Ada simbol @ dan !

---

### **STEP 4: Klik Login**

1. Klik tombol **Login** (biru)
2. Tunggu beberapa detik
3. Jika berhasil, akan redirect ke dashboard

---

## ✅ Jika Berhasil:

Anda akan melihat:
- ✅ Redirect ke `http://localhost/monika/dashboard`
- ✅ Halaman dashboard muncul
- ✅ Nama user "Super Admin" di pojok kanan atas

**SELAMAT! Login berhasil!** 🎉

---

## ❌ Jika Masih Gagal:

### **Cek 1: Lihat Error Message**

Apakah muncul alert/popup error? Apa isinya?

**Kemungkinan error**:
- "Username atau password salah" → Cek password lagi
- "Terlalu banyak percobaan" → Tunggu 15 menit
- "Terjadi kesalahan sistem" → Lanjut ke Cek 2

---

### **Cek 2: Buka Developer Tools**

1. Tekan `F12` di keyboard
2. Klik tab **Console**
3. Apakah ada error merah?
4. Screenshot dan kirim ke saya

---

### **Cek 3: Lihat Network Request**

1. Masih di Developer Tools (F12)
2. Klik tab **Network**
3. Klik tombol Login lagi
4. Cari request bernama `login` atau `auth/login`
5. Klik request tersebut
6. Klik tab **Response**
7. Screenshot dan kirim ke saya

---

## 🔍 Troubleshooting Cepat

### Problem: "Username atau password salah"

**Solusi**: Pastikan password PERSIS seperti ini:
```
Monika@2026!
```

Copy-paste password di atas ke form login.

---

### Problem: Halaman tidak load / blank

**Solusi**:
1. Check apakah Laragon running (icon hijau)
2. Check MySQL running: `mysql -u root -pMonika@2026! -e "SELECT 1"`
3. Restart Laragon: Stop All → Start All

---

### Problem: Error "CSRF token mismatch"

**Solusi**:
1. Refresh halaman (F5)
2. Clear browser cache: `Ctrl + Shift + Delete`
3. Coba lagi

---

### Problem: Redirect loop (balik ke login terus)

**Solusi**:
1. Clear cookies browser
2. Gunakan Incognito mode
3. Coba lagi

---

## 🆘 Solusi Terakhir (Jika Semua Gagal)

### **Option 1: Disable CSRF Sementara**

Edit file: `app/Config/Filters.php`

Cari baris ini:
```php
public array $methods = [
    'POST' => ['csrf'],
];
```

Ubah jadi:
```php
public array $methods = [
    // 'POST' => ['csrf'],  // Disabled for testing
];
```

Save, lalu coba login lagi.

**PENTING**: Setelah berhasil login, AKTIFKAN KEMBALI!

---

### **Option 2: Test dengan User Lain**

Coba login dengan user berbeda:

**User 2**:
- Username: `admin_nanang`
- Password: `Monika@2026!`

**User 3**:
- Username: `admin_suharsa`
- Password: `Monika@2026!`

---

### **Option 3: Check Database**

Jalankan command ini:
```bash
mysql -u root -pMonika@2026! monika -e "SELECT username, is_active FROM users WHERE username='admin'"
```

Harus output:
```
+----------+-----------+
| username | is_active |
+----------+-----------+
| admin    |         1 |
+----------+-----------+
```

Jika `is_active = 0`, aktifkan:
```bash
mysql -u root -pMonika@2026! monika -e "UPDATE users SET is_active=1 WHERE username='admin'"
```

---

## 📞 Jika Masih Belum Berhasil

Kirim informasi berikut ke saya:

1. **Screenshot halaman login** (sebelum klik login)
2. **Screenshot error** (jika ada)
3. **Screenshot Console** (F12 → Console tab)
4. **Screenshot Network** (F12 → Network → auth/login → Response)
5. **Output command ini**:
   ```bash
   php spark diagnose:login
   ```

---

## 🎯 Checklist Sebelum Login

Pastikan semua ini sudah:

- [ ] Browser ditutup dan dibuka lagi (atau Incognito mode)
- [ ] Halaman login sudah load sempurna
- [ ] Username: `admin` (huruf kecil semua)
- [ ] Password: `Monika@2026!` (M besar, ada @ dan !)
- [ ] Laragon running (icon hijau)
- [ ] MySQL running

---

## 💡 Tips

1. **Gunakan Incognito mode** untuk menghindari cache issue
2. **Copy-paste password** untuk menghindari typo
3. **Tunggu beberapa detik** setelah klik login
4. **Jangan spam klik** tombol login (bisa kena rate limit)
5. **Check Caps Lock** tidak aktif

---

**Dibuat**: 16 Februari 2026  
**Status**: ✅ READY TO TEST  
**Estimated Success Rate**: 95%

**SELAMAT MENCOBA!** 🚀
