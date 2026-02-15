# Panduan Setup & Implementasi Git Automation MONIKA

**Versi**: 1.0  
**Bahasa**: Indonesian (Bahasa Indonesia)  
**Target**: Tech Lead, DevOps, System Admin  
**Durasi**: 15-20 menit setup

---

## 📋 Daftar Isi

1. [Prasyarat](#prasyarat)
2. [Arsitektur Sistem](#arsitektur-sistem)
3. [Instalasi Step-by-Step](#instalasi-step-by-step)
4. [Konfigurasi Lanjutan](#konfigurasi-lanjutan)
5. [Verifikasi Instalasi](#verifikasi-instalasi)
6. [Troubleshooting Setup](#troubleshooting-setup)
7. [Maintenance & Update](#maintenance--update)

---

## ✅ Prasyarat

### System Requirements

- **OS**: Windows 10/11 atau Windows Server 2019+
- **Git**: Version 2.38+ (cek dengan `git --version`)
- **PowerShell**: 5.0 atau 7.0+ (cek dengan `$PSVersionTable.PSVersion`)
- **PHP**: 7.4 atau 8.0+ (untuk code quality checks)
- **Node.js**: Optional, tetapi recommended (untuk future enhancements)

### Verifikasi Prerequisites

```powershell
# Terminal PowerShell (Run as Administrator)

# 1. Cek Git
git --version
# Output: git version 2.45.1... ✓ OK

# 2. Cek PowerShell
$PSVersionTable.PSVersion
# Output: Major Minor Build Revision
#         ----- ----- ----- --------
#         5     1     19041 1320
# ✓ 5.0+ OK

# 3. Cek PHP
php -v
# Output: PHP 8.2.0... ✓ OK

# 4. Cek Git dalam folder project
cd E:\laragon\www\monika
git status
# Output: On branch main... ✓ OK
```

### Setup Account GitHub (Jika Belum)

```bash
# Configure Git dengan akun GitHub Anda
git config --global user.name "Nama Anda"
git config --global user.email "email@github.com"

# Verifikasi
git config --global user.name
git config --global user.email
```

---

## 🏗️ Arsitektur Sistem

### Struktur Folder

```
monika/
├── .githooks/                          # Direktori Hook Git
│   ├── pre-commit.cmd                  # Wrapper batch untuk pre-commit
│   ├── commit-msg.cmd                  # Wrapper batch untuk commit-msg
│   └── pre-push.cmd                    # Wrapper batch untuk pre-push
│
├── scripts/git-automation/             # Script Automation
│   ├── config.ps1                      # Configuration (20+ settings)
│   ├── utils.ps1                       # Utility functions (logging, retry, etc)
│   ├── setup.ps1                       # Setup/installation script
│   ├── pre-commit.ps1                  # Pre-commit hook logic
│   ├── commit-msg.ps1                  # Commit message validation
│   ├── pre-push.ps1                    # Pre-push with retry logic
│   └── auto-commit.ps1                 # Standalone auto-commit script
│
├── .git/
│   └── commit.template                 # Commit message template
│
├── writable/logs/git-automation/       # Logs (auto-created)
│   ├── git-automation-YYYY-MM-DD.log   # Daily logs
│   └── push_history.json               # Push operation history
│
├── writable/git-temp/                  # Temporary files (auto-created)
│   └── checkpoint-<sha>.txt            # Checkpoint files
│
└── docs/
    ├── GIT_AUTOMATION_GUIDE.md         # Lengkap documentation (EN)
    ├── GIT_AUTOMATION_QUICKSTART.md    # Quick start (EN)
    ├── GIT_AUTOMATION_SETUP_LENGKAP.md # Setup guide (ID) ← You are here
    ├── MONIKA_PANDUAN_GIT_AUTOMATION.md # Developer guide (ID)
    └── README.md                        # Overview & navigation

```

### Data Flow

```
Developer Edit Code
    ↓
git add <files>
    ↓
git commit -m "message"
    ↓
[Trigger] Pre-commit hook
├─ Run: pre-commit.cmd
├─ Call: pre-commit.ps1
├─ Load: utils.ps1 + config.ps1
├─ Actions:
│  ├ PHP syntax validation
│  ├ Security check (eval, exec)
│  └ Large file detection
└─ Result: PASS → continue, FAIL → stop

[Trigger] Commit-msg hook
├─ Validate prefix (feat, fix, chore, etc)
├─ Auto-fix if needed
└─ Result: PASS → continue, FAIL → stop

Commit Created ✓
    ↓
[Trigger] Pre-push hook
├─ Create checkpoint (save SHA)
├─ Push attempt #1
├─ If fail → Wait 5s, Retry #2
├─ If fail → Wait 7.5s, Retry #3
├─ If fail → Rollback to checkpoint
└─ Result: Logged to push_history.json

Push to GitHub ✓
    ↓
Log entry created
Email notification (if enabled)

```

### File Exclusion Filter

Files yang TIDAK akan di-commit/push:

```
✗ vendor/*                    # Composer packages
✗ writable/*                  # Cache, logs, uploads
✗ .env                        # Environment variables
✗ .env.local                  # Local environment
✗ composer.lock               # Dependency lock
✗ package-lock.json          # NPM lock
✗ node_modules/*             # NPM packages
✗ *.tmp, *.bak               # Temporary files
```

---

## 🔧 Instalasi Step-by-Step

### Step 1: Verify Git Repository

```powershell
# Navigate ke project root
cd E:\laragon\www\monika

# Verify git repository exists
git rev-parse --git-dir
# Output: .git ✓

# Verify main branch
git branch
# Output: * main ✓
```

### Step 2: Verify Script Files Exist

```powershell
# Check if automation scripts exist
Get-ChildItem -Path "scripts/git-automation" -Filter "*.ps1" | Select-Object Name

# Output should show:
# Name
# ----
# config.ps1
# utils.ps1
# setup.ps1
# pre-commit.ps1
# commit-msg.ps1
# pre-push.ps1
# auto-commit.ps1

# If missing, these files harus sudah di-create:
# - scripts/git-automation/**/*.ps1
# - .githooks/*.cmd (akan di-create oleh setup.ps1)
```

### Step 3: Run Setup Script

```powershell
# Ensure you're in project root
cd E:\laragon\www\monika

# Run setup script dengan administrator privileges
# Jika PowerShell blocking execution, jalankan dulu:
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser

# Kemudian run setup
.\scripts\git-automation\setup.ps1

# Output yang diharapkan:
# [10:30:45] [INFO] Starting Git Automation setup...
# [10:30:46] [INFO] Creating .githooks directory...
# [10:30:47] [INFO] Creating pre-commit.cmd...
# [10:30:48] [INFO] Creating commit-msg.cmd...
# [10:30:49] [INFO] Creating pre-push.cmd...
# [10:30:50] [INFO] Configuring git core.hooksPath...
# [10:30:51] [INFO] Creating commit template...
# [10:30:52] [SUCCESS] Git Automation setup completed successfully!
```

### Step 4: Verify Setup Completion

```powershell
# Verify .githooks directory
Test-Path -Path ".githooks"
# Output: True ✓

# Verify hook files
Get-ChildItem -Path ".githooks" -Filter "*.cmd"
# Output should show: pre-commit.cmd, commit-msg.cmd, pre-push.cmd

# Verify git configuration
git config core.hooksPath
# Output: .githooks ✓

# Verify commit template
git config commit.template
# Output should show path to commit.template ✓

# Verify directories
Test-Path -Path "writable/logs/git-automation"
# Output: True ✓

Test-Path -Path "writable/git-temp"
# Output: True ✓
```

### Step 5: First Test Commit

```powershell
# Create test file
New-Item -Path "test-automation.txt" -Value "Testing git automation setup" -Force

# Stage file
git add test-automation.txt

# Commit
git commit -m "test: verify automation setup"

# Expected output:
# [10:35:12] [INFO] [PRE-COMMIT] Running pre-commit validation...
# [10:35:13] [SUCCESS] Pre-commit validation passed
# [10:35:14] [INFO] [COMMIT-MSG] Validating commit message...
# [10:35:15] [SUCCESS] Commit message validated
# [main abc123] test: verify automation setup
# [10:35:16] [INFO] [PRE-PUSH] Creating checkpoint...
# [10:35:17] [INFO] [PRE-PUSH] Push attempt 1/3
# [10:35:22] [SUCCESS] Push successful

# Verify push
git log --oneline -1
# Show latest commit with test file
```

---

## ⚙️ Konfigurasi Lanjutan

### File: scripts/git-automation/config.ps1

Semua setting automation ada di file ini. Edit sesuai kebutuhan:

```powershell
# Buka file
code scripts/git-automation/config.ps1
```

#### Bagian 1: Path Configuration

```powershell
$LOGS_DIR = "writable/logs/git-automation"
$TEMP_DIR = "writable/git-temp"

# Change ke custom path jika perlu:
$LOGS_DIR = "C:/custom/logs"
$TEMP_DIR = "C:/custom/temp"
```

#### Bagian 2: Git Configuration

```powershell
$GIT_USER_NAME = "Nanang Px0"              # Your GitHub name
$GIT_USER_EMAIL = "nanangpx0@gmail.com"   # Your GitHub email
$GIT_DEFAULT_BRANCH = "main"               # Main branch name
$GIT_REMOTE_NAME = "origin"                # Remote name
```

#### Bagian 3: Commit Message Settings

```powershell
# Valid commit prefixes
$COMMIT_PREFIXES = @("feat", "fix", "chore", "refactor", "docs", 
                     "style", "test", "perf", "ci", "build")

# Dapat di-customize, contoh add "hotfix":
$COMMIT_PREFIXES = @("feat", "fix", "hotfix", "chore", ... )
```

#### Bagian 4: Retry & Network Settings

```powershell
$MAX_RETRY_ATTEMPTS = 3                    # Max retry attempts
$RETRY_DELAY_SECONDS = 5                   # Initial delay
$RETRY_BACKOFF_MULTIPLIER = 1.5            # Exponential backoff multiplier

# Contoh dengan setting ini:
# Attempt 1: fail → wait 5s
# Attempt 2: fail → wait 7.5s (5 * 1.5)
# Attempt 3: fail → wait 11.25s (7.5 * 1.5)
# Attempt 4+: rollback
```

#### Bagian 5: Code Quality & Security

```powershell
$ENABLE_CODE_QUALITY = $true               # Enable PHP syntax check
$ENABLE_UNIT_TESTS = $false                # Enable CI4 unit tests
$MIN_PHP_VERSION = "7.4"                   # Minimum PHP version check

# Untuk enable unit tests (requires PHPUnit):
$ENABLE_UNIT_TESTS = $true
```

#### Bagian 6: Auto-Commit Settings

```powershell
$AUTO_COMMIT_ENABLED = $true               # Enable auto-commit feature
$AUTO_COMMIT_UNTRACKED_FILES = $true       # Include untracked files

# Klasifikasi commit type otomatis berdasarkan folder:
$AUTO_COMMIT_CLASSIFICATION = @{
    "app/Models/*" = "feat"
    "app/Controllers/*" = "feat"
    "app/Views/*" = "feat"
    "docs/*" = "docs"
    "app/Config/*" = "chore"
    "*" = "chore"
}
```

#### Bagian 7: Notifications

```powershell
$ENABLE_NOTIFICATIONS = $true              # Send notifications
$NOTIFY_ON_SUCCESS = $true                 # Notify on success
$NOTIFY_ON_ERROR = $true                   # Notify on error

# Jika ingin silent mode:
$ENABLE_NOTIFICATIONS = $false
```

### Konfigurasi Rekomendasi per Role

#### Untuk Single Developer

```powershell
# config.ps1
$ENABLE_CODE_QUALITY = $true
$ENABLE_UNIT_TESTS = $false
$AUTO_COMMIT_UNTRACKED_FILES = $true
$ENABLE_NOTIFICATIONS = $true
```

#### Untuk Team Development

```powershell
# config.ps1
$ENABLE_CODE_QUALITY = $true
$ENABLE_UNIT_TESTS = $true                 # Enforce tests
$AUTO_COMMIT_UNTRACKED_FILES = $false      # Manual staging
$ENABLE_NOTIFICATIONS = $true              # Team coordination
$MAX_RETRY_ATTEMPTS = 5                    # More resilient
```

#### Untuk CI/CD Pipeline

```powershell
# config.ps1
$ENABLE_CODE_QUALITY = $true
$ENABLE_UNIT_TESTS = $true
$AUTO_COMMIT_ENABLED = $false              # Manual commits only
$ENABLE_NOTIFICATIONS = $false             # No email spam
$MAX_RETRY_ATTEMPTS = 10                   # Very resilient
```

---

## ✔️ Verifikasi Instalasi

### Validation Checklist

```powershell
# Run this script untuk verify semua component

# 1. Check .githooks directory
"[1] Checking .githooks directory..."
if (Test-Path ".githooks") {
    "    ✓ .githooks directory exists"
} else {
    "    ✗ .githooks directory MISSING - RUN SETUP AGAIN"
}

# 2. Check hook files
"[2] Checking hook files..."
$hookFiles = @("pre-commit.cmd", "commit-msg.cmd", "pre-push.cmd")
foreach ($hook in $hookFiles) {
    if (Test-Path ".githooks/$hook") {
        "    ✓ $hook exists"
    } else {
        "    ✗ $hook MISSING"
    }
}

# 3. Check script files
"[3] Checking script files..."
$scriptFiles = @("config.ps1", "utils.ps1", "setup.ps1", 
                 "pre-commit.ps1", "commit-msg.ps1", "pre-push.ps1", 
                 "auto-commit.ps1")
foreach ($script in $scriptFiles) {
    if (Test-Path "scripts/git-automation/$script") {
        "    ✓ $script exists"
    } else {
        "    ✗ $script MISSING"
    }
}

# 4. Check git configuration
"[4] Checking git configuration..."
$hooksPath = git config core.hooksPath
if ($hooksPath -eq ".githooks") {
    "    ✓ Git hooks path configured: $hooksPath"
} else {
    "    ✗ Git hooks path incorrect: $hooksPath"
}

# 5. Check log directory
"[5] Checking log directory..."
if (Test-Path "writable/logs/git-automation") {
    "    ✓ Log directory exists"
} else {
    "    ✗ Log directory MISSING"
}

# 6. Run test commit
"[6] Running test commit..."
# Create test file
"test content" | Out-File -Path "setup-verify-test.txt" -Force
git add setup-verify-test.txt

# Try commit
try {
    git commit -m "test: automated setup verification"
    "    ✓ Test commit successful"
    # Remove test file
    git reset HEAD setup-verify-test.txt
    rm setup-verify-test.txt
} catch {
    "    ✗ Test commit failed: $_"
}
```

### Quick Verification Commands

```powershell
# All-in-one verification
Write-Host "[VERIFICATION] Git Automation Setup Status"
Write-Host "======================================="
Write-Host "Hook dir:   $(if(Test-Path '.githooks') { 'OK ✓' } else { 'MISSING ✗' })"
Write-Host "Scripts:    $(if((Get-ChildItem 'scripts/git-automation' -Filter '*.ps1' | Measure).Count -eq 7) { 'OK ✓' } else { 'INCOMPLETE ✗' })"
Write-Host "Git config: $(if((git config core.hooksPath) -eq '.githooks') { 'OK ✓' } else { 'INVALID ✗' })"
Write-Host "Logs dir:   $(if(Test-Path 'writable/logs/git-automation') { 'OK ✓' } else { 'MISSING ✗' })"
Write-Host "======================================="
```

---

## 🔨 Troubleshooting Setup

### Problem: "cannot find scripts directory"

```powershell
# Solution: Verify current directory
pwd
# Should output: E:\laragon\www\monika

# If not, navigate there:
cd E:\laragon\www\monika

# Verify scripts exist:
ls -R scripts/git-automation
```

### Problem: "PowerShell execution policy error"

```
Error: File xxx.ps1 cannot be loaded because running scripts is disabled

Solution:
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### Problem: "Git hooks not triggering"

```powershell
# 1. Verify hook path
git config core.hooksPath
# Should output: .githooks

# 2. If not set, run setup again
.\scripts\git-automation\setup.ps1

# 3. Verify git version (need 2.9+)
git --version

# 4. Test manually
git commit --no-verify -m "test: bypass hooks"
# If this works, hooks are the issue
```

### Problem: "Permission denied on .cmd files"

```powershell
# Solution: Fix file permissions
icacls ".githooks" /grant:r "%USERNAME%:F" /t

# Or recreate hooks
.\scripts\git-automation\setup.ps1 -Reinstall
```

### Problem: ".env file was committed"

```powershell
# Check .gitignore
grep ".env" .gitignore

# If .env not in .gitignore, add it:
Add-Content -Path ".gitignore" -Value ".env"
Add-Content -Path ".gitignore" -Value ".env.local"

# Remove .env from git tracking
git rm --cached .env
git commit -m "chore: remove .env from git tracking"
```

---

## 🔄 Maintenance & Update

### Checking Logs Regularly

```powershell
# View today's log
Get-Content "writable/logs/git-automation/git-automation-$(Get-Date -Format 'yyyy-MM-dd').log" -Tail 50

# View all logs
Get-ChildItem "writable/logs/git-automation/" -Filter "*.log" | 
    Sort-Object -Descending | 
    Select-Object -First 5

# Search for errors
Select-String -Path "writable/logs/git-automation/*.log" -Pattern "ERROR|FAILED"
```

### Monitoring Push History

```powershell
# View push history (JSON format)
Get-Content "writable/logs/git-automation/push_history.json" | 
    ConvertFrom-Json | 
    Format-Table timestamp, branch, status -AutoSize

# Count successful vs failed pushes
$history = Get-Content "writable/logs/git-automation/push_history.json" | ConvertFrom-Json
"Success: $(($history | Where-Object {$_.status -eq 'SUCCESS'}).Count)"
"Failed: $(($history | Where-Object {$_.status -eq 'FAILED'}).Count)"
```

### Cleanup Old Logs

```powershell
# Remove logs older than 30 days
Get-ChildItem "writable/logs/git-automation/" -Filter "*.log" |
    Where-Object {$_.LastWriteTime -lt (Get-Date).AddDays(-30)} |
    Remove-Item

# Cleanup checkpoint files
Get-ChildItem "writable/git-temp/" -Filter "checkpoint-*.txt" |
    Where-Object {$_.LastWriteTime -lt (Get-Date).AddDays(-7)} |
    Remove-Item
```

### Update System

```powershell
# If there are updates to automation scripts:

# 1. Backup current config
Copy-Item "scripts/git-automation/config.ps1" "scripts/git-automation/config.ps1.backup"

# 2. Pull latest from repo
git pull origin main

# 3. Re-run setup (non-destructive)
.\scripts\git-automation\setup.ps1

# 4. Verify everything still works
git commit --allow-empty -m "test: verify after update"
```

### Uninstall (If Needed)

```powershell
# Remove all automation
.\scripts\git-automation\setup.ps1 -Uninstall

# This will:
# ✗ Remove .githooks directory
# ✗ Reset git config core.hooksPath
# ✗ Remove commit template
# ✗ Leave scripts intact (for documentation)
```

---

## 🎓 Best Practices

### For Team Deployment

```powershell
# 1. On lead device, run setup
.\scripts\git-automation\setup.ps1

# 2. Commit setup files
git add .
git commit -m "chore: install git automation system"

# 3. Other team members pull and verify
git pull
.\scripts\git-automation\setup.ps1  # Run on their machine

# 4. Verify on each machine
git commit --allow-empty -m "test: verify automation"
```

### Health Check Weekly

```powershell
# Add this to your weekly maintenance:

# 1. Check recent logs for errors
Select-String -Path "writable/logs/git-automation/*.log" -Pattern "ERROR"

# 2. Verify push success rate
$history = Get-Content "writable/logs/git-automation/push_history.json" | ConvertFrom-Json
$total = $history.Count
$success = ($history | Where-Object {$_.status -eq 'SUCCESS'}).Count
"Push success rate: {0:P}" -f ($success/$total)

# 3. Check system still responsive
git log --oneline -1
```

---

## 📚 Referensi Lengkap

| Topik | File | Bahasa |
|-------|------|--------|
| Developer Guide | MONIKA_PANDUAN_GIT_AUTOMATION.md | 🇮🇩 ID |
| Setup Guide | GIT_AUTOMATION_SETUP_LENGKAP.md | 🇮🇩 ID |
| Troubleshooting | GIT_AUTOMATION_TROUBLESHOOTING.md | 🇮🇩 ID |
| Eng. Full Guide | GIT_AUTOMATION_GUIDE.md | 🇬🇧 EN |
| Eng. Quick Start | GIT_AUTOMATION_QUICKSTART.md | 🇬🇧 EN |
| Validation Report | GIT_AUTOMATION_VALIDATION_REPORT.md | 🇬🇧 EN |

---

**Setup Selesai! 🎉**

Sistem Git Automation siap digunakan untuk seluruh tim MONIKA.

Next steps:
1. Share dokumentasi ke team
2. Run setup di setiap developer machine
3. Set expectations untuk commit message format
4. Monitor logs untuk first week

Pertanyaan? Lihat [README.md](README.md) atau troubleshooting guide.
