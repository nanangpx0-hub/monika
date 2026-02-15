# MONIKA - Utility Scripts

Kumpulan script PowerShell untuk membantu development dan maintenance aplikasi MONIKA.

---

## 📋 Daftar Script

### 1. `update-docs.ps1` - Auto-Update Documentation

Script untuk mengupdate dokumentasi otomatis berdasarkan perubahan di codebase.

#### Penggunaan:

**Update semua dokumentasi:**
```powershell
.\scripts\update-docs.ps1
```

**Update changelog dengan pesan:**
```powershell
.\scripts\update-docs.ps1 -Type changelog -Message "Menambahkan fitur Uji Petik Kualitas"
```

**Update struktur project saja:**
```powershell
.\scripts\update-docs.ps1 -Type structure
```

**Update daftar fitur saja:**
```powershell
.\scripts\update-docs.ps1 -Type features
```

#### Parameter:

- `-Type` : Jenis update (all, changelog, structure, features)
  - `all` (default): Update semua dokumentasi
  - `changelog`: Update CHANGELOG.md
  - `structure`: Update PROJECT_STRUCTURE.md
  - `features`: Update FEATURES_LIST.md

- `-Message` : Pesan perubahan (wajib untuk type changelog)

#### Output:

Script akan mengupdate file-file berikut:
- `CHANGELOG.md` - Log perubahan aplikasi
- `docs/PROJECT_STRUCTURE.md` - Struktur project
- `docs/FEATURES_LIST.md` - Daftar fitur yang ada

---

### 2. `generate-api-docs.ps1` - Generate API Documentation

Script untuk generate dokumentasi API dari controller.

#### Penggunaan:

```powershell
.\scripts\generate-api-docs.ps1
```

#### Output:

- `docs/API_DOCUMENTATION.md` - Dokumentasi lengkap API endpoints

---

### 3. `check-migrations.ps1` - Check Migration Status

Script untuk mengecek status migration database.

#### Penggunaan:

```powershell
.\scripts\check-migrations.ps1
```

#### Output:

Menampilkan:
- Migration yang sudah dijalankan
- Migration yang belum dijalankan
- Tabel database yang ada

---

## 🚀 Quick Start

### Setup (Pertama Kali)

1. Buka PowerShell di folder project
2. Jalankan command berikut untuk allow script execution:
   ```powershell
   Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
   ```

### Workflow Setelah Membuat Perubahan

1. **Setelah menambah fitur baru:**
   ```powershell
   .\scripts\update-docs.ps1 -Type changelog -Message "Menambahkan modul Uji Petik Kualitas"
   ```

2. **Setelah mengubah struktur project:**
   ```powershell
   .\scripts\update-docs.ps1 -Type structure
   ```

3. **Update semua dokumentasi sekaligus:**
   ```powershell
   .\scripts\update-docs.ps1 -Type all -Message "Refactor Kartu Kendali dengan subquery"
   ```

---

## 📝 Tips & Best Practices

### 1. Update Dokumentasi Secara Berkala

Jalankan script setiap kali:
- Menambah controller/model baru
- Menambah fitur baru
- Mengubah struktur database
- Sebelum commit ke Git

### 2. Pesan Changelog yang Baik

Gunakan format yang jelas:
```powershell
# ✅ BAIK
.\scripts\update-docs.ps1 -Type changelog -Message "Menambahkan fitur export Excel di laporan"

# ❌ KURANG BAIK
.\scripts\update-docs.ps1 -Type changelog -Message "update"
```

### 3. Kombinasi dengan Git

```powershell
# Update docs
.\scripts\update-docs.ps1 -Type all -Message "Implementasi modul Logistik lengkap"

# Commit changes
git add .
git commit -m "feat: Implementasi modul Logistik lengkap"
git push
```

---

## 🔧 Troubleshooting

### Error: "Execution Policy"

**Problem:**
```
.\scripts\update-docs.ps1 : File cannot be loaded because running scripts is disabled
```

**Solution:**
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### Error: "Path not found"

**Problem:**
Script tidak menemukan folder project.

**Solution:**
Pastikan menjalankan script dari root folder project (folder yang ada composer.json).

### Script Tidak Update File

**Problem:**
File dokumentasi tidak berubah.

**Solution:**
1. Cek apakah folder `docs/` ada
2. Cek permission file (read-only?)
3. Jalankan PowerShell sebagai Administrator

---

## 📚 Dokumentasi yang Dihasilkan

### CHANGELOG.md
Log perubahan aplikasi dengan format:
```markdown
## [2026-02-15] - Latest Changes

### Added
- Menambahkan modul Uji Petik Kualitas
- Refactor Kartu Kendali dengan subquery approach
```

### docs/PROJECT_STRUCTURE.md
Struktur lengkap project:
- Daftar Controllers
- Daftar Models dengan nama tabel
- Daftar Views
- Daftar Migrations
- Directory tree

### docs/FEATURES_LIST.md
Daftar fitur yang sudah diimplementasi:
- Modul-modul yang ada
- Status implementasi
- Statistik project

---

## 🎯 Roadmap Script

### Coming Soon:
- [ ] `backup-database.ps1` - Backup database otomatis
- [ ] `deploy.ps1` - Deploy ke production
- [ ] `test-runner.ps1` - Run automated tests
- [ ] `code-quality.ps1` - Check code quality

---

## 📞 Support

Jika ada masalah atau pertanyaan:
1. Cek dokumentasi di atas
2. Cek error message di terminal
3. Hubungi tim development

---

**Last Updated:** 2026-02-15  
**Maintained by:** MONIKA Development Team
