# 📚 Dokumentasi MONIKA

**Status**: ✅ Production Ready  
**Last Updated**: Februari 15, 2026  
**Version**: 1.1  
**Maintained By**: DevOps & Development Team MONIKA

Index ini mencakup dokumentasi Git Automation dan modul aplikasi MONIKA.

---

## 🎯 Quick Navigation

Pilih dokumentasi sesuai kebutuhan Anda:

### 🧾 Saya Petugas Pengolahan (Kartu Kendali)

```
Tujuan: Menjalankan entry ruta per NKS

Baca:
1. [Panduan Pengguna Kartu Kendali](KARTU_KENDALI_USER_GUIDE.md)
   - Alur kerja harian
   - Arti status warna ruta
   - Entry, edit, dan hapus data

2. Untuk detail teknis:
   → [Dokumentasi Modul Kartu Kendali](KARTU_KENDALI_MODULE.md)
```

### 👨‍💻 Saya Developer MONIKA

```
Tujuan: Memahami cara commit & push code dengan automation

Baca:
1. [Panduan Developer MONIKA](MONIKA_PANDUAN_GIT_AUTOMATION.md)
   - 15 menit: Alur kerja harian
   - Format commit message
   - Contoh praktis
   - Tips & trik

2. Jika ada masalah:
   → [Troubleshooting & FAQ](GIT_AUTOMATION_TROUBLESHOOTING.md)
```

### 🔧 Saya Tech Lead / DevOps

```
Tujuan: Setup sistem untuk team

Baca:
1. [Panduan Setup Lengkap](GIT_AUTOMATION_SETUP_LENGKAP.md)
   - 20 menit: Instalasi step-by-step
   - Konfigurasi lanjutan
   - Verifikasi setup
   
2. [Laporan Validasi](GIT_AUTOMATION_VALIDATION_REPORT.md)
   - Status verifikasi 8 test
   - Production ready checklist

3. Troubleshooting:
   → [Troubleshooting & FAQ](GIT_AUTOMATION_TROUBLESHOOTING.md)
```

### 🚀 Saya Mau Quick Start (5 menit)

```
Tujuan: Setup & test cepat

Baca:
[GIT_AUTOMATION_QUICKSTART.md](GIT_AUTOMATION_QUICKSTART.md)

Includes:
- Prerequisite checklist
- Quick setup commands
- Commit message examples
- Testing procedure
```

### 📖 Saya Mau Dokumentasi Lengkap

```
Tujuan: Mengerti semua aspek sistem

Baca:
[GIT_AUTOMATION_GUIDE.md](GIT_AUTOMATION_GUIDE.md)

Covers:
- Architecture & design
- Detailed workflow
- Advanced configuration
- Security & best practices
- Complete API reference

Bahasa: English (Lengkap)
Durasi: 1-2 jam membaca
```

### 🆘 Ada Error / Masalah

```
Tujuan: Debug & fix issue

Baca:
[Troubleshooting & FAQ](GIT_AUTOMATION_TROUBLESHOOTING.md)

Sections:
- Quick diagnosis guide
- Error messages & solutions
- Common problems
- FAQ (20+ pertanyaan)
- Emergency recovery
```

---

## 📋 Dokumentasi Lengkap

| Dokumen | Bahasa | Durasi | Untuk Siapa | Topik Utama |
|---------|--------|--------|-----------|-----------|
| **MONIKA_PANDUAN_GIT_AUTOMATION.md** | 🇮🇩 ID | 15 menit | Developer | Alur kerja, format commit, contoh praktis |
| **GIT_AUTOMATION_SETUP_LENGKAP.md** | 🇮🇩 ID | 20 menit | Tech Lead | Setup lengkap, konfigurasi, maintenance |
| **GIT_AUTOMATION_TROUBLESHOOTING.md** | 🇮🇩 ID | 30 menit | Semua | Diagnosis, error solutions, FAQ |
| **GIT_AUTOMATION_GUIDE.md** | 🇬🇧 EN | 1-2 jam | Architect | Deep dive, complete reference |
| **GIT_AUTOMATION_QUICKSTART.md** | 🇬🇧 EN | 5 menit | New User | Quick setup, basic usage |
| **GIT_AUTOMATION_VALIDATION_REPORT.md** | 🇬🇧 EN | 15 menit | Tech Lead | Verification, test results |
| **KARTU_KENDALI_USER_GUIDE.md** | 🇮🇩 ID | 10 menit | Petugas Pengolahan | Panduan penggunaan fitur Kartu Kendali |
| **KARTU_KENDALI_MODULE.md** | 🇮🇩 ID | 10 menit | Developer | Deskripsi modul, route, API, dan testing |

---

## 🔄 Workflow Automation

### Apa yang Terjadi Otomatis?

```
You do this          You type this               System does this
─────────────────────────────────────────────────────────────────────

Edit code        1. git add <files>       ✓ Stage files
                                    ↓
                 2. git commit -m    ✓ Pre-commit hook runs
                    "message"          • Validasi syntax PHP
                                       • Cek security issues
                                       • Check large files
                                    ↓
                                       ✓ Commit-msg hook
                                       • Validasi prefix
                                       • Auto-fix format
                                    ↓
                                       ✓ Create commit
                                    ↓
                                       ✓ Pre-push hook
                                       • Create checkpoint
                                       • Push ke GitHub
                                       • Retry if fail
                                       • Rollback if all fail
                                    ↓
                                       ✓ Push successful
                                       • Log operation
                                       • Send notification

OR use auto-script:  .\auto-commit.ps1  ✓ Detect changes
                                       ✓ Classify type
                                       ✓ Stage files
                                       ✓ Generate message
                                       ✓ Commit & push
                                       ✓ Complete cycle
```

### Statistics

```
Setup Time:           15-20 minutes
Test Time:            5 minutes
Normal Commit Time:   2-10 seconds
Failure Recovery:     Automatic (3 retries)
File Size Limit:      10MB per file
Supported Languages:  PHP, JavaScript, HTML, CSS, SQL
Supported OS:         Windows 10/11, Windows Server 2019+
```

---

## 🎓 Learning Path

### For New Developers

```
Day 1: Understand Git Automation
├─ Read: MONIKA_PANDUAN_GIT_AUTOMATION.md (15 min)
├─ Run: First test commit
└─ Ask: Questions tentang format

Day 2: Practice
├─ Make 5 commits dengan format correct
├─ Try different commit types (feat, fix, docs)
└─ Check GitHub untuk verify push

Day 3: Troubleshooting
├─ Intro ke TROUBLESHOOTING.md (bagian relevant only)
├─ Know where to find answers
└─ Ready for production
```

### For System Administrators

```
Week 1: Setup & Configuration
├─ Day 1: Read GIT_AUTOMATION_SETUP_LENGKAP.md
├─ Day 2: Run setup di dev machine
├─ Day 3: Verify dengan validation checklist
└─ Day 4: Test error scenarios

Week 2: Deploy to Team
├─ Day 1-2: Setup di setiap developer machine
├─ Day 3: Training session untuk team
├─ Day 4: Monitor logs, troubleshoot
└─ Day 5: Documentation sharing

Week 3-4: Ongoing
├─ Monitor logs daily
├─ Run health checks weekly
├─ Collect feedback dari team
├─ Fine-tune configuration
└─ Document team-specific patterns
```

---

## 💡 Key Concepts

### Git Hooks

```
Pre-commit Hook
├─ When: Runs saat "git commit", SEBELUM commit created
├─ What: Validasi code quality
├─ If fail: Commit dibatalkan
└─ File: .githooks/pre-commit.cmd → scripts/git-automation/pre-commit.ps1

Commit-msg Hook
├─ When: Runs SETELAH user enter message, SEBELUM commit saved
├─ What: Validasi & format commit message
├─ If fail: Commit dibatalkan
└─ File: .githooks/commit-msg.cmd → scripts/git-automation/commit-msg.ps1

Pre-push Hook
├─ When: Runs sebelum push ke GitHub
├─ What: Push dengan retry & rollback safety
├─ If fail: Automatic rollback, commit saved locally
└─ File: .githooks/pre-push.cmd → scripts/git-automation/pre-push.ps1
```

### Retry Mechanism

```
Push attempt #1: Immediate
  ↓ If fail, wait 5 seconds
Push attempt #2: After 5s
  ↓ If fail, wait 7.5 seconds (5 × 1.5)
Push attempt #3: After 7.5s
  ↓ If fail, give up
Rollback: Revert commit locally, keep data safe
```

### File Exclusion

```
Automatically EXCLUDED (tidak di-commit):
├─ vendor/*               ← Composer packages
├─ node_modules/*         ← NPM packages
├─ writable/*             ← Cache, logs, uploads
├─ .env                   ← Environment variables (IMPORTANT!)
├─ .env.local             ← Local override
├─ composer.lock          ← Dependency lock file
├─ package-lock.json      ← NPM lock file
└─ *.tmp, *.bak          ← Temporary files

Tip: Edit .github/workflows/ untuk exclude lebih banyak
```

---

## 📊 System Architecture

```
Git Automation Architecture
═══════════════════════════════════

┌─────────────────────────────────────────────────────────────┐
│  Developer Workflow                                         │
│  ├─ Edit code                                               │
│  ├─ git add <files>                                         │
│  ├─ git commit -m "message"                                 │
│  └─ [Automation triggers]                                   │
└─────────────────────────────────────────────────────────────┘
                         ↓
┌─────────────────────────────────────────────────────────────┐
│  Pre-commit Hook (Validation Layer)                         │
│  ├─ Check PHP syntax errors                                 │
│  ├─ Detect security vulnerabilities                         │
│  ├─ Validate file sizes                                     │
│  └─ Result: PASS → continue, FAIL → stop                    │
└─────────────────────────────────────────────────────────────┘
                         ↓
┌─────────────────────────────────────────────────────────────┐
│  Commit-msg Hook (Format Layer)                             │
│  ├─ Validate prefix (feat, fix, chore, etc)                │
│  ├─ Check message length                                    │
│  ├─ Auto-fix formatting                                     │
│  └─ Result: Formatted message saved                         │
└─────────────────────────────────────────────────────────────┘
                         ↓
┌─────────────────────────────────────────────────────────────┐
│  Commit Created ✓                                           │
│  └─ Local history updated                                   │
└─────────────────────────────────────────────────────────────┘
                         ↓
┌─────────────────────────────────────────────────────────────┐
│  Pre-push Hook (Push Layer)                                 │
│  ├─ Create checkpoint (save SHA)                            │
│  ├─ Push attempt #1                                         │
│  ├─ Retry logic:                                            │
│  │  ├─ If fail → wait 5s, retry #2                          │
│  │  ├─ If fail → wait 7.5s, retry #3                        │
│  │  └─ If fail → Rollback execution                         │
│  ├─ Log operation (success/fail)                            │
│  └─ Send notification                                       │
└─────────────────────────────────────────────────────────────┘
                         ↓
┌─────────────────────────────────────────────────────────────┐
│  GitHub Remote                                              │
│  └─ Commit pushed & visible                                 │
└─────────────────────────────────────────────────────────────┘
```

### File Structure

```
monika/
├── .githooks/                          # Git hooks directory
│   ├── pre-commit.cmd
│   ├── commit-msg.cmd
│   └── pre-push.cmd
│
├── scripts/git-automation/             # Automation scripts
│   ├── config.ps1                      # Configuration
│   ├── utils.ps1                       # Shared functions
│   ├── setup.ps1                       # Setup script
│   ├── pre-commit.ps1
│   ├── commit-msg.ps1
│   ├── pre-push.ps1
│   └── auto-commit.ps1
│
├── writable/logs/git-automation/       # Logs
│   ├── git-automation-2026-02-15.log
│   ├── git-automation-2026-02-14.log
│   └── push_history.json
│
├── writable/git-temp/                  # Checkpoint files
│   └── checkpoint-abc123def.txt
│
└── docs/                               # Documentation
    ├── README.md                       # This file
    ├── MONIKA_PANDUAN_GIT_AUTOMATION.md
    ├── GIT_AUTOMATION_SETUP_LENGKAP.md
    ├── GIT_AUTOMATION_TROUBLESHOOTING.md
    ├── GIT_AUTOMATION_GUIDE.md
    ├── GIT_AUTOMATION_QUICKSTART.md
    └── GIT_AUTOMATION_VALIDATION_REPORT.md
```

---

## ✅ Checklist

### First-Time Setup

- [ ] Read MONIKA_PANDUAN_GIT_AUTOMATION.md
- [ ] Run .\scripts\git-automation\setup.ps1
- [ ] Verify setup dengan validation checklist
- [ ] Make test commit
- [ ] Verify push to GitHub
- [ ] Share documentation dengan team

### Before First Commit

- [ ] Understand commit message format
- [ ] Know what files you're committing
- [ ] Have pulled latest from GitHub
- [ ] Tested code locally

### During Development

- [ ] Follow commit message format
- [ ] Stage only related files (focused commits)
- [ ] Review changes dengan `git diff`
- [ ] Push frequently (daily minimum)

### Team Maintenance

- [ ] Monitor logs for issues
- [ ] Run health check weekly
- [ ] Update documentation as needed
- [ ] Share common issues/solutions

---

## 🔗 External Resources

| Topic | Link |
|-------|------|
| Git Official Documentation | https://git-scm.com/doc |
| GitHub Help | https://docs.github.com |
| Git Workflow Best Practices | https://www.atlassian.com/git/tutorials/comparing-workflows |
| Commit Message Conventions | https://www.conventionalcommits.org |
| PHP Syntax Validation | https://www.php.net/manual/en/features.commandline.options.php#id1 |

---

## 📞 Support & FAQ

### How is documentation organized?

```
README.md (You are here)
├─ Quick navigation
├─ Learning paths
└─ Points to specific docs

Target-Specific Docs:
├─ MONIKA_PANDUAN_GIT_AUTOMATION.md → For developers
├─ GIT_AUTOMATION_SETUP_LENGKAP.md → For DevOps
├─ GIT_AUTOMATION_TROUBLESHOOTING.md → For troubleshooting
├─ GIT_AUTOMATION_QUICKSTART.md → For quick start
├─ GIT_AUTOMATION_GUIDE.md → For deep dive (EN)
└─ GIT_AUTOMATION_VALIDATION_REPORT.md → For verification
```

### Where are logs stored?

```
Daily logs:
writable/logs/git-automation/git-automation-YYYY-MM-DD.log

Push history (JSON):
writable/logs/git-automation/push_history.json

Checkpoint files:
writable/git-temp/checkpoint-<sha>.txt
```

### What if something breaks?

```
1. Check logs first:
   writable/logs/git-automation/git-automation-*.log

2. Search this documentation:
   GIT_AUTOMATION_TROUBLESHOOTING.md

3. Try manual commands:
   git push origin main (manual push)
   git status (check status)
   git log --oneline (check history)

4. If still stuck:
   Run setup.ps1 again → often fixes issues
```

---

## 🚀 Quick Commands

```powershell
# Normal workflow
git add .
git commit -m "feat(your-feature): description"
# [Automation handles the rest]

# OR auto-commit
.\scripts\git-automation\auto-commit.ps1

# Check status
git status
git log --oneline -5

# Manual debug
git push origin main --verbose

# Check logs
Get-Content writable/logs/git-automation/git-automation-*.log -Tail 50

# Verify setup
.\scripts\git-automation\setup.ps1

# Uninstall (if needed)
.\scripts\git-automation\setup.ps1 -Uninstall
```

---

## 📈 Status

| Component | Status | Last Verified |
|-----------|--------|--------------|
| Pre-commit Hook | ✅ Operational | Feb 15, 2026 |
| Commit-msg Hook | ✅ Operational | Feb 15, 2026 |
| Pre-push Hook | ✅ Operational | Feb 15, 2026 |
| Auto-commit Script | ✅ Operational | Feb 15, 2026 |
| Logging System | ✅ Operational | Feb 15, 2026 |
| GitHub Integration | ✅ Synced | Feb 15, 2026 |
| .env Protection | ✅ Verified | Feb 15, 2026 |

**Overall Status**: ✅ **PRODUCTION READY**

---

## 📝 Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.1 | Feb 15, 2026 | Add Kartu Kendali documentation index and user guide links |
| 1.0 | Feb 15, 2026 | Initial release, all components tested |

---

## 👥 Contributing

Untuk improve dokumentasi:

1. Identify issue atau improvement
2. Clone/edit relevant documentation file
3. Test changes
4. Commit dengan: `docs: [description]`
5. Push normally (automation handles it)

---

## 📄 License

Dokumentasi ini adalah bagian dari MONIKA project.
Follow project license untuk usage details.

---

## 🙏 Acknowledgments

Sistem ini dirancang untuk:
- ✅ Keamanan (security checks, .env protection)
- ✅ Reliability (retry mechanism, rollback)
- ✅ Consistency (commit message format)
- ✅ Ease of use (fully automated)
- ✅ Team collaboration (logging, notifications)

---

## 🎯 Next Steps

**For Pengolahan Team:**
→ Start dengan [KARTU_KENDALI_USER_GUIDE.md](KARTU_KENDALI_USER_GUIDE.md)

**For Developers:**
→ Start dengan [MONIKA_PANDUAN_GIT_AUTOMATION.md](MONIKA_PANDUAN_GIT_AUTOMATION.md)

**For DevOps/Tech Lead:**
→ Start dengan [GIT_AUTOMATION_SETUP_LENGKAP.md](GIT_AUTOMATION_SETUP_LENGKAP.md)

**For Quick Overview:**
→ Read [GIT_AUTOMATION_QUICKSTART.md](GIT_AUTOMATION_QUICKSTART.md)

**For Troubleshooting:**
→ Go to [GIT_AUTOMATION_TROUBLESHOOTING.md](GIT_AUTOMATION_TROUBLESHOOTING.md)

---

**Last Updated**: Februari 15, 2026  
**Maintained By**: DevOps & Development Team MONIKA  
**Status**: ✅ Production Ready

---

📞 **Questions?** Start by checking the relevant documentation above.  
🐛 **Found a bug?** Check troubleshooting or logs first.  
💡 **Have suggestions?** Commit a documentation improvement!

**Happy Building with MONIKA!**
