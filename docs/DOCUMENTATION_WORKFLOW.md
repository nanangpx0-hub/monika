# MONIKA - Documentation Workflow Guide

**Last Updated:** 2026-02-15

Panduan lengkap untuk mengelola dokumentasi aplikasi MONIKA menggunakan automation scripts.

---

## 📋 Overview

MONIKA menyediakan script otomatis untuk:
1. Update changelog
2. Generate struktur project
3. Generate daftar fitur
4. Generate API documentation
5. Check migration status

---

## 🚀 Quick Start

### Cara Tercepat (Windows)

Gunakan file `.bat` yang sudah disediakan:

```cmd
update-docs.bat "Menambahkan modul Uji Petik Kualitas"
```

### Cara Manual (PowerShell)

```powershell
.\scripts\update-docs.ps1 -Type all -Message "Menambahkan modul Uji Petik Kualitas"
```

---

## 📝 Workflow Dokumentasi

### 1. Setelah Menambah Fitur Baru

**Scenario:** Anda baru saja membuat modul baru (Controller, Model, Views)

**Command:**
```powershell
.\scripts\update-docs.ps1 -Type all -Message "Implementasi modul Logistik lengkap"
```

**Hasil:**
- ✅ CHANGELOG.md diupdate dengan entry baru
- ✅ PROJECT_STRUCTURE.md diupdate dengan controller/model baru
- ✅ FEATURES_LIST.md diupdate dengan fitur baru

---

### 2. Setelah Refactor Code

**Scenario:** Anda melakukan refactoring tanpa menambah fitur baru

**Command:**
```powershell
.\scripts\update-docs.ps1 -Type changelog -Message "Refactor KartuKendaliModel menggunakan subquery"
```

**Hasil:**
- ✅ CHANGELOG.md diupdate
- ⏭️ Struktur dan fitur tidak berubah (skip)

---

### 3. Setelah Menambah Migration

**Scenario:** Anda membuat migration baru untuk tabel database

**Step 1 - Update docs:**
```powershell
.\scripts\update-docs.ps1 -Type all -Message "Menambahkan tabel logistik_items"
```

**Step 2 - Check migration:**
```powershell
.\scripts\check-migrations.ps1
```

**Step 3 - Run migration:**
```bash
php spark migrate
```

---

### 4. Generate API Documentation

**Scenario:** Anda ingin membuat dokumentasi API untuk frontend developer

**Command:**
```powershell
.\scripts\generate-api-docs.ps1
```

**Hasil:**
- ✅ docs/API_DOCUMENTATION.md dibuat/diupdate
- Berisi semua endpoint dari controller

---

### 5. Check Project Status

**Scenario:** Anda ingin melihat overview project

**Command:**
```powershell
# Check migrations
.\scripts\check-migrations.ps1

# Update structure
.\scripts\update-docs.ps1 -Type structure

# Update features
.\scripts\update-docs.ps1 -Type features
```

---

## 📂 File Dokumentasi yang Dihasilkan

### 1. CHANGELOG.md (Root)

**Lokasi:** `CHANGELOG.md`

**Isi:**
- Log perubahan aplikasi
- Dikelompokkan per tanggal
- Format: Added, Changed, Fixed, Removed

**Contoh:**
```markdown
## [2026-02-15] - Latest Changes

### Added
- Menambahkan modul Uji Petik Kualitas
- Implementasi export Excel untuk laporan

### Changed
- Refactor KartuKendaliModel menggunakan subquery

### Fixed
- Perbaikan error only_full_group_by di query
```

---

### 2. PROJECT_STRUCTURE.md

**Lokasi:** `docs/PROJECT_STRUCTURE.md`

**Isi:**
- Daftar semua Controllers
- Daftar semua Models dengan nama tabel
- Daftar semua Views
- Daftar semua Migrations
- Directory tree

**Update:** Otomatis setiap kali ada perubahan struktur

---

### 3. FEATURES_LIST.md

**Lokasi:** `docs/FEATURES_LIST.md`

**Isi:**
- Daftar fitur yang sudah diimplementasi
- Status implementasi per modul
- Statistik project (jumlah controller, model, dll)

**Update:** Otomatis setiap kali ada perubahan fitur

---

### 4. API_DOCUMENTATION.md

**Lokasi:** `docs/API_DOCUMENTATION.md`

**Isi:**
- Daftar semua API endpoints
- HTTP method untuk setiap endpoint
- Parameter yang diperlukan
- Response format
- Contoh request/response

**Update:** Manual dengan command `generate-api-docs.ps1`

---

## 🔄 Workflow Integration dengan Git

### Workflow Lengkap

```powershell
# 1. Buat fitur baru
# ... coding ...

# 2. Update dokumentasi
.\scripts\update-docs.ps1 -Type all -Message "Implementasi modul Logistik"

# 3. Generate API docs (optional)
.\scripts\generate-api-docs.ps1

# 4. Check status
git status

# 5. Add changes
git add .

# 6. Commit
git commit -m "feat: Implementasi modul Logistik lengkap"

# 7. Push
git push origin main
```

---

## 📊 Best Practices

### 1. Update Dokumentasi Secara Berkala

✅ **DO:**
- Update setiap kali menambah fitur
- Update sebelum commit
- Update setelah refactor besar

❌ **DON'T:**
- Menunda update dokumentasi
- Commit tanpa update docs
- Mengabaikan changelog

---

### 2. Pesan Changelog yang Baik

✅ **GOOD:**
```powershell
"Menambahkan fitur export Excel di modul Laporan"
"Refactor KartuKendaliModel untuk menghindari SQL error"
"Perbaikan bug pada form Tanda Terima"
```

❌ **BAD:**
```powershell
"update"
"fix"
"changes"
```

---

### 3. Kategorisasi Perubahan

Gunakan prefix untuk clarity:

- **feat:** Fitur baru
  ```
  "feat: Implementasi modul Logistik"
  ```

- **fix:** Bug fix
  ```
  "fix: Perbaikan error pada form validation"
  ```

- **refactor:** Refactoring code
  ```
  "refactor: Optimasi query dengan subquery"
  ```

- **docs:** Update dokumentasi
  ```
  "docs: Menambahkan panduan deployment"
  ```

- **style:** Perubahan styling
  ```
  "style: Update warna button di Kartu Kendali"
  ```

---

## 🛠️ Troubleshooting

### Problem 1: Script Tidak Jalan

**Error:**
```
.\scripts\update-docs.ps1 : File cannot be loaded
```

**Solution:**
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

---

### Problem 2: File Tidak Terupdate

**Possible Causes:**
1. File read-only
2. Permission issue
3. Path salah

**Solution:**
```powershell
# Check file attributes
Get-ItemProperty .\CHANGELOG.md | Select-Object Attributes

# Remove read-only
Set-ItemProperty .\CHANGELOG.md -Name IsReadOnly -Value $false

# Run as Administrator
```

---

### Problem 3: Migration Check Error

**Error:**
```
Could not run 'php spark migrate:status'
```

**Solution:**
1. Pastikan PHP ada di PATH
2. Pastikan database configured di `.env`
3. Test manual: `php spark migrate:status`

---

## 📅 Maintenance Schedule

### Daily
- Update changelog setelah setiap perubahan

### Weekly
- Generate API documentation
- Review PROJECT_STRUCTURE.md
- Review FEATURES_LIST.md

### Monthly
- Cleanup old changelog entries
- Archive dokumentasi lama
- Update README.md

---

## 🎯 Checklist Sebelum Commit

```
[ ] Code sudah di-test
[ ] Dokumentasi sudah diupdate (update-docs.ps1)
[ ] CHANGELOG.md sudah ada entry baru
[ ] API docs sudah diupdate (jika ada perubahan endpoint)
[ ] Migration sudah dijalankan (jika ada)
[ ] Git status clean
[ ] Commit message jelas
```

---

## 📞 Support

Jika ada masalah:
1. Cek dokumentasi ini
2. Cek `scripts/README.md`
3. Cek error message di terminal
4. Hubungi tim development

---

## 🔗 Related Documentation

- [scripts/README.md](../scripts/README.md) - Script documentation
- [CHANGELOG.md](../CHANGELOG.md) - Change log
- [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md) - Project structure
- [FEATURES_LIST.md](FEATURES_LIST.md) - Features list
- [API_DOCUMENTATION.md](API_DOCUMENTATION.md) - API docs

---

**Maintained by:** MONIKA Development Team  
**Last Updated:** 2026-02-15
