# Panduan Git Automation untuk Developer MONIKA

**Versión**: 1.0  
**Bahasa**: Indonesian (Bahasa Indonesia)  
**Terakhir Diperbarui**: Februari 15, 2026  
**Target Audience**: Developer MONIKA

---

## 📋 Daftar Isi

1. [Pengenalan Singkat](#pengenalan-singkat)
2. [Alur Kerja Harian](#alur-kerja-harian)
3. [Format Commit Message](#format-commit-message)
4. [Contoh Praktis](#contoh-praktis)
5. [Tips & Trik](#tips--trik)
6. [Yang HARUS dan Yang JANGAN](#yang-harus-dan-yang-jangan)
7. [Emergency Commands](#emergency-commands)

---

## 🎯 Pengenalan Singkat

Sistem Git Automation akan **otomatis menjalankan validasi dan push code Anda ke GitHub**. Anda tidak perlu khawatir dengan command Git yang kompleks.

**Yang terjadi otomatis:**
- ✅ Validasi syntax PHP sebelum commit
- ✅ Cek skenario keamanan (eval, exec, system)
- ✅ Format commit message secara otomatis
- ✅ Push ke GitHub dengan retry otomatis
- ✅ Rollback otomatis jika ada error
- ✅ Semua operasi tercatat dalam log

---

## 🔄 Alur Kerja Harian

### Workflow Developer MONIKA

```
1. Buat/Edit file MONIKA
   ↓
2. Terminal: git add .
   (atau git add file_spesifik.php)
   ↓
3. Terminal: git commit -m "deskripsi perubahan"
   ↓
4. [OTOMATIS] Pre-commit Hook
   • Validasi syntax PHP
   • Cek keamanan
   ↓
5. [OTOMATIS] Commit-msg Hook
   • Format message
   • Validasi prefix
   ↓
6. [OTOMATIS] Pre-push Hook
   • Buat checkpoint
   • Push ke GitHub
   • Retry jika gagal (3x attempt)
   • Rollback jika gagal total
   ↓
7. ✅ Selesai! Code Anda ada di GitHub
```

### Alternatif: Gunakan Auto-Commit Script

Jika Anda ingin semuanya **fully automated**, gunakan:

```powershell
# Terminal PowerShell di root project
.\scripts\git-automation\auto-commit.ps1
```

Script ini akan:
1. Deteksi file yang berubah
2. Classify jenis perubahan (feat/fix/docs/chore)
3. Generate commit message otomatis
4. Stage, commit, dan push semuanya

---

## 📝 Format Commit Message

### Format Standar

```
<prefiks>(<scope>): <deskripsi singkat>
```

### Prefiks yang Diizinkan

| Prefiks | Untuk Apa | Contoh |
|---------|-----------|--------|
| `feat` | Fitur baru | `feat(presensi): tambah validasi telat` |
| `fix` | Bug fix | `fix(laporan): perbaiki sorting kolom` |
| `chore` | Maintenance, config | `chore(config): update database host` |
| `docs` | Dokumentasi | `docs: update panduan setup` |
| `refactor` | Reorganisasi kode | `refactor(models): pindahkan function query` |
| `test` | Test/QA | `test(presensi): tambah unit test` |
| `style` | Formatting, style | `style: fix indentation` |
| `perf` | Performance | `perf(laporan): optimize query performance` |
| `ci` | CI/CD related | `ci: update github actions` |
| `build` | Build related | `build: update dependencies` |

### Aturan

✅ **BOLEH:**
- `feat: tambah fitur dashboard baru`
- `fix(monitoring): perbaiki bug filter tanggal`
- `docs: update dokumentasi API`
- `chore: update vendor packages`

❌ **JANGAN:**
- `update file` (terlalu vague)
- `FIX BUG` (uppercase, tidak ada deskripsi spesifik)
- `feat (presensi)` (spasi di depan scope)
- `feat presensi: ...` (tanda titik dua di tempat yang salah)

### Deskripsi Yang Baik

```
❌ Buruk:
git commit -m "update"
git commit -m "fix bug"
git commit -m "tambah kode"

✅ Baik:
git commit -m "feat(presensi): tambah validasi automat telat >30 menit"
git commit -m "fix(kartu-kendali): perbaiki overlay modal tidak tertutup"
git commit -m "docs(api): lengkapi dokumentasi endpoint presensi"
```

**Tip**: Bayangkan log Anda 6 bulan kemudian. Apakah text yang Anda tulis akan membantu developer lain memahami perubahan?

---

## 💡 Contoh Praktis

### Contoh 1: Menambah Fitur Presensi

```bash
# 1. Buat/edit file presensi
# Vi/edit:
# - app/Controllers/Presensi.php
# - app/Models/PresensiModel.php
# - app/Views/presensi/index.php

# 2. Stage files
git add app/Controllers/Presensi.php
git add app/Models/PresensiModel.php
git add app/Views/presensi/index.php

# 3. Commit dengan message yang descriptive
git commit -m "feat(presensi): implement location-based check-in with map"

# 4. OTOMATIS:
# [1] Pre-commit hook: validasi syntax ✅
# [2] Commit-msg hook: format message ✅
# [3] Pre-push hook: push ke GitHub ✅

# Output:
# ✓ Pre-commit validation passed
# ✓ Commit message formatted
# ✓ Push attempt 1/3: SUCCESS
# ✓ Committed to GitHub
```

### Contoh 2: Bug Fix di Laporan

```bash
# 1. Perbaiki bug
# Edit: app/Models/LaporanModel.php

# 2. Stage dan commit
git add app/Models/LaporanModel.php
git commit -m "fix(laporan): correct sum calculation for monthly report"

# Output yang diharapkan:
# ✓ Code quality check passed
# ✓ Commit: fix(laporan): correct sum calculation for monthly report
# ✓ Push attempt 1/3: SUCCESS
```

### Contoh 3: Update Dokumentasi

```bash
# 1. Update docs
# Edit: docs/README.md

# 2. Stage dan commit
git add docs/README.md
git commit -m "docs: add FAQ section and troubleshooting guide"

# Output:
# ✓ No code files detected (docs only)
# ✓ Commit: docs: add FAQ section and troubleshooting guide
# ✓ Pushed to GitHub
```

### Contoh 4: Multiple Files - Fitur Kompleks

```bash
# Fitur: Sistem approval untuk monitorning

git add app/Controllers/Monitoring.php
git add app/Models/MonitoringModel.php  
git add app/Views/monitoring/approval.php
git add public/assets/js/approval.js

git commit -m "feat(monitoring): add approval workflow with email notifications"

# System akan:
# 1. Detect 4 files yang berubah
# 2. Validasi semua PHP files
# 3. Generate message: "feat(monitoring): update 4 file(s)"
# 4. Push semuanya dalam 1 commit
```

---

## 🎓 Tips & Trik

### Tip 1: Cek Status Sebelum Commit

```bash
git status
# Output:
# modified:   app/Controllers/Presensi.php
# modified:   app/Models/PresensiModel.php
```

### Tip 2: Cek Apa yang Akan di-Commit

```bash
git diff app/Models/PresensiModel.php
# Lihat perubahan sebelum stage
```

### Tip 3: Stage Selective Files

```bash
# Stage hanya file tertentu
git add app/Controllers/Presensi.php

# Hindari stage writable/ atau vendor/
# (Otomatis di-exclude oleh system)
```

### Tip 4: Lihat Log Commit Terbaru

```bash
git log --oneline -5
# Lihat 5 commit terakhir

git log --oneline --graph --all
# Lihat history dengan visual
```

### Tip 5: Review Sebelum Push

```bash
git log --oneline origin/main..HEAD
# Lihat commit yang belum di-push
```

---

## ✅ Yang HARUS dan ❌ Yang JANGAN

### ✅ HARUS Dilakukan

| Item | Penjelasan | Contoh |
|------|-----------|--------|
| Write descriptive messages | Jelas dan informatif | `feat(presensi): add QR code check-in` |
| Use proper prefixes | Gunakan standar prefix | `fix:`, `feat:`, `docs:` |
| Stage related files | Group perubahan yang relevan | Presensi controller + model + view |
| Keep commits focused | 1 commit = 1 fitur atau 1 bug | Jangan campur fitur beda dalam 1 commit |
| Review changes before commit | Cek dengan `git diff` | Pastikan tidak ada typo/salah |
| Push regularly | Jangan accumulate too many commits | Push 1-2x per hari minimal |

### ❌ JANGAN Dilakukan

| Item | Masalah | Contoh Salah |
|------|---------|-------------|
| Tidak menulis deskripsi | Sulit tracking | `git commit -m "fix"` |
| Mix berbagai perubahan | Sulit revert jika ada bug | Fitur presensi + fix laporan dalam 1 commit |
| Stage semua file random | Akumulasi file tidak perlu | `git add .` tanpa seleksi |
| Commit ke repo pribadi | Data sensitif | Commit .env atau database dump |
| Force push ke main | Kehilangan history | `git push --force` |
| Ignore error messages | Bisa jadi masalah besar | "Ayo kita ignored dulu error-nya" |
| Long commit messages | Sulit dibaca | Paragraf panjang dalam 1 commit |

---

## 🚨 Emergency Commands

### Kasus 1: Mau Undo Commit Terakhir (Belt-out Push)

```bash
# Undo commit, keep changes
git reset --soft HEAD~1

# Edit lagi jika perlu

# Commit ulang dengan message yang benar
git commit -m "feat(correct-name): proper description"
```

### Kasus 2: Kena Error pada Pre-commit

```
❌ ERROR: Pre-commit validation failed
   Syntax error in app/Controllers/Presensi.php line 42

✓ Fix:
1. Buka file dan perbaiki syntax error
2. Stage ulang: git add app/Controllers/Presensi.php
3. Commit lagi: git commit -m "feat(...): your message"
```

### Kasus 3: Commit Sudah di-Push tapi Ada Salah

```bash
# Jika baru saja di-push (< 2 menit):
git reset --soft HEAD~1
# Edit file
git add file-yang-diperbaiki.php
git commit -m "fix: perbaikan sebelumnya"
git push  # Akan di-push dengan automatically

# Jika sudah lama atau di-push ke branch lain:
# Buat commit baru yang nyangkul
git commit -m "fix: perbaiki issue di previous commit"
git push  # Push fix baru
```

### Kasus 4: Lihat Semua Perubahan Belum Di-Push

```bash
git log --oneline origin/main..HEAD
# Lihat semua commits yang belum di-push
```

### Kasus 5: Pull Latest Changes dari GitHub

```bash
git pull origin main
# Update local repo dengan latest dari GitHub

# Jika ada conflict:
# 1. Edit file yang conflict (markers >>> <<<)
# 2. git add file-yang-sudah-diperbaiki.php
# 3. git commit -m "merge: resolve conflict dari pull"
```

---

## 📊 Monitoring Aktivitas

### Lihat Log Automation

Semua aktivitas automation tercatat di:

```
writable/logs/git-automation/git-automation-YYYY-MM-DD.log
```

**Contoh log:**
```
[2026-02-15 10:30:45] [INFO] Repository status: 3 modified, 0 added, 0 deleted
[2026-02-15 10:30:46] [INFO] Pre-commit validation started
[2026-02-15 10:30:47] [SUCCESS] Pre-commit validation passed  
[2026-02-15 10:30:48] [INFO] Commit created: abc123 (feat: update models)
[2026-02-15 10:30:49] [INFO] Push attempt 1/3
[2026-02-15 10:30:54] [SUCCESS] Push successful
```

### Cek Push History

```
writable/logs/git-automation/push_history.json
```

Format JSON dengan timestamp, branch, sha, dan status setiap push.

---

## 🔗 Referensi & Link

| Topik | File |
|-------|------|
| Panduan Lengkap | [GIT_AUTOMATION_GUIDE.md](GIT_AUTOMATION_GUIDE.md) |
| Quick Start | [GIT_AUTOMATION_QUICKSTART.md](GIT_AUTOMATION_QUICKSTART.md) |
| Setup Awal | [GIT_AUTOMATION_SETUP_LENGKAP.md](GIT_AUTOMATION_SETUP_LENGKAP.md) |
| Troubleshooting | [GIT_AUTOMATION_TROUBLESHOOTING.md](GIT_AUTOMATION_TROUBLESHOOTING.md) |
| Laporan Validasi | [GIT_AUTOMATION_VALIDATION_REPORT.md](GIT_AUTOMATION_VALIDATION_REPORT.md) |

---

## ❓ Pertanyaan Umum

**T: Apakah saya perlu push secara manual?**  
J: Tidak. Setelah `git commit`, push terjadi otomatis melalui pre-push hook.

**T: Bagaimana kalau internet mati saat push?**  
J: System akan retry otomatis 3x dengan delay. Jika tetap gagal, akan ada rollback. Coba push ulang kemudian.

**T: Bisa tidak ikuti format commit message?**  
J: Boleh, tapi commit-msg hook akan auto-fix. Contoh: "fixed bug" → "fix: fixed bug".

**T: Gimana jika ada file .env?**  
J: .env otomatis di-exclude. Aman tidak ter-push ke GitHub.

**T: Bisa cek apa yang sudah di-push?**  
J: `git log --oneline origin/main -10` untuk lihat 10 commit terakhir di GitHub.

---

## 📞 Support

Jika ada masalah atau pertanyaan:

1. **Cek dokumentasi**: Start di [README.md](README.md)
2. **Lihat log**: `writable/logs/git-automation/git-automation-*.log`
3. **Coba troubleshooting**: [GIT_AUTOMATION_TROUBLESHOOTING.md](GIT_AUTOMATION_TROUBLESHOOTING.md)
4. **Manual recovery**: [GIT_AUTOMATION_GUIDE.md](GIT_AUTOMATION_GUIDE.md#manual-recovery)

---

**Happy Coding! 🚀**

> Sistem Git Automation membuat workflow Anda lebih aman, cepat, dan reliable. Fokus ke feature development, biarkan automation handle Git operations.
