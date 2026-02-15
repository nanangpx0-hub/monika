# Panduan Troubleshooting & FAQ Git Automation MONIKA

**Versi**: 1.0  
**Bahasa**: Indonesian (Bahasa Indonesia)  
**Last Updated**: Februari 15, 2026  
**Target Audience**: Developer MONIKA, DevOps Team

---

## 📋 Daftar Isi

1. [Quick Diagnosis](#quick-diagnosis)
2. [Error Messages & Solutions](#error-messages--solutions)
3. [Common Problems](#common-problems)
4. [FAQ - Pertanyaan Umum](#faq---pertanyaan-umum)
5. [Advanced Troubleshooting](#advanced-troubleshooting)
6. [Emergency Recovery](#emergency-recovery)
7. [Getting Help](#getting-help)

---

## 🔍 Quick Diagnosis

### Step 1: Identify the Problem

Gunakan diagram ini untuk quick diagnose:

```
Masalah terjadi saat...

├─ `git add .` atau `git add <file>`
│  └─ Biasanya BUKAN Git automation issue
│     → Check file path, permissions, .gitignore
│
├─ `git commit -m "message"`
│  ├─ Commit failed / blocked
│  │  → Lihat: PRE-COMMIT ERRORS
│  │
│  ├─ Commit OK tapi message di-ubah otomatis
│  │  → Lihat: COMMIT-MSG ISSUES
│  │
│  └─ Commit created tapi push tidak terjadi
│     → Lihat: PRE-PUSH ISSUES
│
├─ `.\scripts\git-automation\auto-commit.ps1`
│  ├─ Script tidak run
│  │  → Lihat: SCRIPT EXECUTION ISSUES
│  │
│  └─ Script run tapi error
│     → Lihat: ERROR MESSAGES & SOLUTIONS
│
└─ Log files atau GitHub not updated
   └─ Lihat: POST-PUSH ISSUES
```

### Step 2: Check Basic Setup

```powershell
# Run ini untuk quick check
Write-Host "=== QUICK DIAGNOSTICS ===" -ForegroundColor Cyan
Write-Host ""

# 1. Check Git
$gitExists = (Get-Command git -ErrorAction SilentlyContinue) -ne $null
Write-Host "Git installed: $(if($gitExists) {'✓'} else {'✗'})"

# 2. Check PowerShell
Write-Host "PowerShell version: $($PSVersionTable.PSVersion.Major).$($PSVersionTable.PSVersion.Minor)"

# 3. Check Automation
$hookFlag = git config core.hooksPath
Write-Host "Hooks path configured: $(if($hookFlag -eq '.githooks') {'✓'} else {'✗'})"

# 4. Check Scripts
$scriptCount = (Get-ChildItem "scripts/git-automation" -Filter "*.ps1" | Measure).Count
Write-Host "Automation scripts: $scriptCount/7 files"

# 5. Check Directories
$logDir = Test-Path "writable/logs/git-automation"
Write-Host "Log directory exists: $(if($logDir) {'✓'} else {'✗'})"

Write-Host ""
if ($gitExists -and $hookFlag -eq '.githooks' -and $scriptCount -eq 7 -and $logDir) {
    Write-Host "STATUS: All basic checks PASSED ✓" -ForegroundColor Green
} else {
    Write-Host "STATUS: Some checks FAILED - Run setup.ps1" -ForegroundColor Red
}
```

---

## ❌ Error Messages & Solutions

### PRE-COMMIT ERRORS

#### Error 1: "PHP syntax error in [file]"

```
❌ ERROR:
[PRE-COMMIT] PHP syntax error in app/Controllers/Presensi.php:42
Parse error: syntax error, unexpected TOKEN...

✓ SOLUTION:
1. Open file: app/Controllers/Presensi.php
2. Go to line 42
3. Fix syntax error (missing semicolon, bracket, etc)
   
   Example:
   ❌ public function index() {    // Missing semicolon above
   ❌     $data = ['name' => 'value]   // Missing closing quote
   
   ✓ public function index() {
   ✓     $data = ['name' => 'value'];
   
4. Stage file again: git add app/Controllers/Presensi.php
5. Commit again: git commit -m "fix: syntax error in presensi"

TIP: Run php -l <file> untuk quick syntax check:
php -l app/Controllers/Presensi.php
```

#### Error 2: "Security issue detected: eval() with variable"

```
❌ ERROR:
[PRE-COMMIT] Security issue detected in app/Models/RoleModel.php:85
Potential security vulnerability: eval() with variable

✓ SOLUTION:
1. Open file: app/Models/RoleModel.php, line 85
2. Find line with eval(), exec(), system(), atau passthru()
3. Refactor to use safer alternatives:

   ❌ UNSAFE:
   eval($query);
   exec($command);
   system($userInput);
   
   ✓ SAFE:
   // Use eval alternative:
   $query = json_decode($query, true);  // if from JSON
   
   // Use exec alternative:
   shell_exec($command);  // with proper escaping
   escapeshellarg($arg);   // for arguments
   
   // Use system alternative:
   Call proper function instead:
   $result = file_get_contents('path');

4. Re-add file: git add app/Models/RoleModel.php
5. Try commit again

LEARN MORE:
https://owasp.org/www-community/attacks/Code_Injection
```

#### Error 3: "File too large (> 10MB)"

```
❌ ERROR:
[PRE-COMMIT] File too large detected: public/uploads/video.mp4 (150MB)
Large files should not be committed to Git repository

✓ SOLUTION:

Option A: Remove large file from staging
- git reset HEAD public/uploads/video.mp4
- Add to .gitignore: echo "public/uploads/*.mp4" >> .gitignore
- Commit again

Option B: Use Git LFS (Large File Storage)
- Install Git LFS: https://git-lfs.com/
- Track file type: git lfs track "*.mp4"
- Re-add: git add public/uploads/video.mp4
- Commit: git commit -m "feat: add video file with LFS"

Option C: Remove from history if already committed:
- git rm --cached public/uploads/video.mp4
- git commit --amend --no-edit
- git push --force-with-lease

BEST PRACTICE:
- Max file size: 10MB per file
- Use Git LFS for media files
- Store binaries externally (S3, CDN)
```

#### Error 4: "Unit test failed"

```
❌ ERROR:
[PRE-COMMIT] Unit tests failed
Running 15 tests... 3 failed

App\Tests\Models\PresensiModelTest::testCreateValidData FAILED
App\Tests\Models\PresensiModelTest::testValidateCheckIn FAILED

✓ SOLUTION:
1. Run tests manually to see details:
   php vendor/bin/phpunit tests/unit/Models/PresensiModelTest.php
   
2. Fix failing tests:
   - Check test file: tests/unit/Models/PresensiModelTest.php
   - Understand what test expects
   - Fix either test or code
   
3. Re-run tests locally:
   php vendor/bin/phpunit
   
4. Once all pass, stage and commit:
   git add tests/
   git add app/Models/
   git commit -m "test(presensi): fix unit test cases"

TEMPORARY FIX (not recommended):
- Disable unit tests: config.ps1 → $ENABLE_UNIT_TESTS = $false
- Only for investigation, re-enable after fix!
```

---

### COMMIT-MSG ERRORS

#### Error 5: "Invalid commit prefix"

```
❌ ERROR:
commit message validation failed
"BUGFIX: typo in login page" - Invalid prefix "BUGFIX"
Valid prefixes: feat, fix, chore, refactor, docs, style, test, perf, ci, build

✓ SOLUTION:
The system akan auto-fix ini, tapi jika tidak:

1. Understand valid prefixes:
   ✓ feat: new feature
   ✓ fix: bug fix
   ✓ chore: maintenance
   ✓ docs: documentation
   ✓ refactor: code reorganization
   
2. Re-commit dengan prefix yang benar:
   git commit --amend -m "fix: typo in login page"
   
   (--amend = edit the most recent commit)

3. Or interactive rebase to fix multiple commits:
   git rebase -i HEAD~5     # Last 5 commits
   
   Then in editor:
   reword <hash> BUGFIX message → fix: correct message
   reword <hash> UPDATE message → chore: update code
```

#### Error 6: "Message too short"

```
❌ ERROR:
commit message too vague or too short.
Be more specific about what changed.

Current: "fix: small bug"
Should be: "fix: [specific description]" or longer

✓ SOLUTION:
Auto-fix akan add boilerplate message.
Untuk lebih deskriptif:

1. Re-commit with better message:
   git commit --amend -m "fix(login): correct password validation regex"
   
2. Test prefix + description:
   Valid: "fix(module): specific change description"
   Length: At least 10 characters recommended

TIPS:
✓ Good: "fix(presensi): correct time display for late checkout"
✓ Good: "feat(dashboard): add monthly report chart"
❌ Bad: "fix bug" (too short, not specific)
❌ Bad: "update stuff" (vague, not actionable)
```

---

### PRE-PUSH ERRORS

#### Error 7: "Network error - Push failed"

```
❌ ERROR:
[PRE-PUSH] Push attempt 1/3 failed
fatal: unable to access 'https://github.com/nanangpx0-hub/monika.git/': 
Connection timed out

✓ SOLUTION:

Step 1: Check internet connection
- ping github.com
- Test dalam browser: https://github.com/status

Step 2: Manual push untuk debug
git push origin main --verbose
# This shows full output

Step 3: If still failing - Try alternatives:
Option A: Wait & retry
- System auto-retry dalam 5-7-11 detik
- Biasanya OK setelah retry

Option B: Manual push
> powershell
git push origin main
# If successful, no further action needed

Option C: Check authentication
git config credential.helper
# Should show: wincred atau cache

# Re-add credentials:
git credential reject https://github.com
git push  # Will ask for credentials

Step 4: Check GitHub status
- Visit: https://github.com/status
- If GitHub down, wait and retry later

LOGS:
Check writable/logs/git-automation/git-automation-YYYY-MM-DD.log
untuk push details.
```

#### Error 8: "Rollback executed - Push failed permanently"

```
❌ ERROR:
[PRE-PUSH] All 3 push attempts failed
[PRE-PUSH] Executing rollback...
[ROLLBACK] Reset to checkpoint: abc123
[ROLLBACK] Commit history preserved locally

! IMPORTANT: Commit NOT pushed to GitHub

✓ SOLUTION:

Step 1: Understand the situation
- Your commit is saved LOCALLY
- NOT on GitHub
- Can re-push once network fixed

Step 2: Check local commits
git log --oneline -5
# Should show your commit

Step 3: Verify files still there
git status
# Should show clean

Step 4: Retry push manually
git push origin main

# If SUCCESS:
✓ Commit now on GitHub
✓ You're good to go

# If FAIL (network still down):
- Wait for network to recover
- Try again in few minutes
- Check GitHub status page

Step 5: If push still unreliable
Option A: Use different network
- Try mobile hotspot
- Try different WiFi

Option B: Use SSH instead of HTTPS
git remote -v
# Shows: origin https://...

# Switch to SSH:
git remote set-url origin git@github.com:nanangpx0-hub/monika.git
git push origin main

CHECKPOINT RECOVERY:
If rollback didn't work as expected:
git reflog                          # Show all commits
git reset --hard <specific-commit>  # Reset if needed
```

#### Error 9: "Remote rejected by server"

```
❌ ERROR:
[PRE-PUSH] Push rejected by GitHub
remote: Permission denied
fatal: could not read from remote repository

✓ SOLUTION:

Cause: Authentication issue

Step 1: Check GitHub authentication
git remote -v
# Verify remote URL correct

Step 2: Check credentials
Windows:
- Search "Credential Manager" dalam Start menu
- Find "github.com" entries
- Delete them to re-authenticate

macOS:
- Keychain Access > internet passwords > github.com
- Delete old entries

Linux:
git credential reject https://github.com

Step 3: Re-authenticate
git push origin main
# Browser window akan muncul untuk login

Step 4: Alternative - Use SSH Key
# If HTTPS tidak work, gunakan SSH:

# Generate key (if not exist):
ssh-keygen -t ed25519 -C "email@github.com"

# Add to SSH agent:
ssh-add ~/.ssh/id_ed25519

# Add public key to GitHub:
# 1. Copy: cat ~/.ssh/id_ed25519.pub
# 2. GitHub Settings > SSH Keys > Add

# Switch remote to SSH:
git remote set-url origin git@github.com:nanangpx0-hub/monika.git

# Test:
git push origin main
```

---

### SCRIPT EXECUTION ERRORS

#### Error 10: "Script cannot be loaded - execution policy"

```
❌ ERROR:
.\scripts\git-automation\auto-commit.ps1 : File cannot be loaded because 
running scripts is disabled on this system.

✓ SOLUTION:

PowerShell execution policy terlalu ketat.

Step 1: Check current policy
Get-ExecutionPolicy
# Output: Restricted ← Problem

Step 2: Set proper policy
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
# Prompt: Change execution policy? [Y]es

Step 3: Verify
Get-ExecutionPolicy
# Output: RemoteSigned ✓

Step 4: Try script again
.\scripts\git-automation\auto-commit.ps1

EXPLANATION:
- Restricted = No scripts allowed
- RemoteSigned = Local scripts OK, downloaded need signature
- Unrestricted = All scripts allowed (less safe)

SCOPE OPTIONS:
-Scope CurrentUser  = Only logged-in user
-Scope LocalMachine = All users on computer (need admin)
```

#### Error 11: "Module not found - utils.ps1"

```
❌ ERROR:
Cannot find a Plan Provider with name null or missing required parameter.
Cannot find the help file for this Topic in the module.
Cannot load utils.ps1

✓ SOLUTION:

File path issue atau sourcing problem.

Step 1: Verify file exists
Test-Path "scripts/git-automation/utils.ps1"
# Should output: True

Step 2: Check current directory
pwd
# Should output: E:\laragon\www\monika

# If not, navigate:
cd E:\laragon\www\monika

Step 3: Verify script can be sourced
. "./scripts/git-automation/utils.ps1"
# If no error, sourcing works

Step 4: Try script again
.\scripts\git-automation\auto-commit.ps1

Step 5: If still failing
# Check for syntax errors:
PS> Test-Path "scripts/git-automation/*.ps1" | ForEach-Object {
    Write-Host "Testing $(Resolve-Path $_)"
    . $_
}
```

#### Error 12: "Git command not found"

```
❌ ERROR:
'git' is not recognized as an internal or external command,
operable program or batch file.

✓ SOLUTION:

Git tidak terinstall atau tidak dalam PATH.

Step 1: Verify Git installation
git --version
# If error → Git not installed

Step 2: Install Git
# Download: https://git-scm.com/download/win
# Run installer dengan default settings
# Restart PowerShell

Step 3: Verify after install
git --version
# Output: git version 2.x.x ✓

Step 4: Configure Git
git config --global user.name "Nama Anda"
git config --global user.email "email@github.com"

Step 5: Try automation script
.\scripts\git-automation\auto-commit.ps1
```

---

## 🔧 Common Problems

### Problem 1: Commits tidak auto-push

```
Symptoms:
- Commit created ✓
- Tapi GitHub tidak update
- Logs show push success, but GitHub old

Diagnosis:
1. Check local commits:
   git log --oneline -5

2. Check remote commits:
   git log --oneline -5 origin/main

3. Compare:
   If local ahead of remote → Push belum sync

Solutions:

A. Manual push:
   git push origin main

B. Check remote URL:
   git remote -v
   Should show: https://github.com/nanangpx0-hub/monika.git

C. Force sync:
   git fetch origin
   git reset --hard origin/main
   (Warning: Akan replace local dengan remote)

D. Check push_history:
   Get-Content writable/logs/git-automation/push_history.json | Select -Last 3
```

### Problem 2: Hook tidak trigger saat commit

```
Symptoms:
- git commit works instantly
- No validation messages
- Tidak ada log entry

Cause 1: core.hooksPath tidak set
Solution:
git config core.hooksPath
# If empty: git config core.hooksPath .githooks

Cause 2: .githooks missing atau corrupt
Solution:
.\scripts\git-automation\setup.ps1 --Reinstall

Cause 3: Git version terlalu lama (< 2.9)
Solution:
git --version
# Update if < 2.9: https://git-scm.com/download/win

Cause 4: Commit dengan --no-verify flag
Solution:
Don't use: git commit --no-verify
Normal: git commit -m "message"
```

### Problem 3: .env file accidentally committed

```
Symptoms:
- .env file on GitHub
- Not supposed to be there
- Security risk

Recovery Steps:

Step 1: Remove from Git tracking
git rm --cached .env

Step 2: Add to .gitignore
echo ".env" >> .gitignore
git add .gitignore

Step 3: Commit removal
git commit -m "chore: remove .env from git tracking"

Step 4: Push
git push origin main

Step 5: Clean from history (optional but recommended)
# If .env dalam history, need more aggressive cleanup:
git filter-branch --tree-filter "rm -f .env" -- --all

Step 6: Force push (careful!)
git push origin main --force-with-lease

⚠️ WARNING:
- Setelah ini, harus pull ulang rebase untuk team
- Communicate dengan team sebelum force push

PREVENTION:
- Verify .env in .gitignore BEFORE first commit
- Use .env.example sebagai template
- Add .env.local untuk local overrides
```

### Problem 4: Wrong file committed

```
Symptoms:
- Accidentally staged file yang tidak perlu
- File sudah committed ke GitHub
- Need untuk undo

Recovery:

Step 1: Soft reset (keep changes locally)
git reset --soft HEAD~1
# Commit undo tapi files back to staging

Step 2: Unstage wrong file
git reset HEAD <file-yang-salah>

Step 3: Remove dari working directory jika perlu
rm <file-yang-salah>
# Or restore previous version:
git checkout HEAD -- <file-yang-salah>

Step 4: Re-stage correct files
git add <file-yang-benar>

Step 5: Commit again
git commit -m "feat: correct commit"

Step 6: Push
git push origin main

If already pushed:
# Create new commit to fix:
git rm <file-yang-salah>
git commit -m "chore: remove accidentally committed file"
git push origin main
```

### Problem 5: Merge conflict saat pull

```
Symptoms:
git pull origin main
fatal: Not possible to fast-forward, aborting
# atau
conflict in <file>

Resolution:

Step 1: Check status
git status
# Shows conflicting files

Step 2: View conflict
git diff <file-dengan-conflict>
# Shows markers: <<<<<<, ======, >>>>>>

Step 3: Edit file manually
# Look for:
<<<<<<< HEAD
your changes
=======
remote changes
>>>>>>>

# Edit ke:
final merged version

Step 4: Stage resolved file
git add <file-resolved>

Step 5: Complete merge
git commit -m "merge: resolve conflict from pull"

Step 6: Push
git push origin main

ATAU Abort merge:
git merge --abort
# Start over

BEST PRACTICE:
- Pull frequently (daily/hourly)
- Keep commits small
- Communicate dengan team tentang siapa kerja di file apa
```

---

## ❓ FAQ - Pertanyaan Umum

### Q1: Apakah saya perlu install atau setup sesuatu?

```
A: Hanya pertama kali:

1. Run setup script:
   .\scripts\git-automation\setup.ps1

2. Happens once, then setiap commit/push otomatis

3. Jika perlu uninstall:
   .\scripts\git-automation\setup.ps1 -Uninstall
```

### Q2: Bisa tidak ikuti commit message format?

```
A: Technically bisa, tapi:

- System auto-fix ke format yang valid
- Example: "bugfix: typo" → "fix: typo"

Better: Ikuti format sejak awal
- Lebih konsistent
- Lebih mudah di-review
- History lebih clean
```

### Q3: Bagaimana jika internet mati saat push?

```
A: System auto-handle:

1. Detect network error
2. Retry otomatis:
   - Attempt 1 (immediately)
   - Attempt 2 (wait 5 seconds)
   - Attempt 3 (wait 7.5 seconds)
3. Jika semua gagal:
   - Create checkpoint
   - Rollback locally
   - Commit tetap aman locally
4. Retry manual saat internet kembali:
   git push

Commit tidak akan hilang, hanya pending push.
```

### Q4: Bisa run auto-commit dengan custom message?

```
A: Ya:

.\scripts\git-automation\auto-commit.ps1 -CustomMessage "feat(presensi): custom message here"

Output:
- Akan staged all changed files
- Commit dengan message yang Anda provide
- Push otomatis
```

### Q5: Bisakah disable validation untuk cepat?

```
A: Tidak recommended, tapi bisa:

git commit --no-verify -m "message"
# Bypass semua hooks

TAPI:
⚠️ Bisa jadi error tidak ketahuan
⚠️ Code quality tidak terjamin
⚠️ Masalah keamanan tidak tercek

BETTER: Fix issue dulu, commit normal
- Lebih reliable
- Safer untuk team
- Lebih consistent
```

### Q6: Bisa cek yang sudah di-commit tapi belum di-push?

```
A: Ya:

git log --oneline origin/main..HEAD
# Shows commits waiting for push

git log --oneline -5
# Compare dengan:

git log --oneline -5 origin/main
# jika berbeda → ada pending push
```

### Q7: Bagaimana jika .env case sensitive terjadi?

```
A: Remove dari git:

git rm --cached .env
git rm --cached .ENV
# (jika misalnya ada dua file case berbeda)

git commit -m "chore: remove .env case variants"

Then add to .gitignore:
echo ".env*" >> .gitignore
```

### Q8: Bisa revert commit yang sudah di-push?

```
A: Ya, using revert:

git revert <commit-hash>
# Creates NEW commit yang undo perubahan

Then:
git push

OR Hard reset (powerful, careful):

git reset --hard <previous-commit>
git push --force-with-lease

⚠️ Force push affects team, communicate dulu!
```

### Q9: Berapa lama proses commit+push normal?

```
A: Bergantung:

- Small changes (1-2 files): 1-2 seconds
- Medium changes (5-10 files): 5-10 seconds
- Large changes (50+ files): 10-30 seconds
- dengan network issue: dapat lebih lama

Average dengan automation: 5-10 seconds

Jika lebih dari 30 detik:
- Check internet speed
- Check GitHub status
- Review untuk large file
```

### Q10: Log file bisa di-share atau delete?

```
A: Boleh:

Share:
- writable/logs/git-automation/git-automation-*.log
- Gunakan untuk debugging atau audit

Delete:
- Aman untuk delete log lama (>30 hari)
- Automation akan recreate

Jangan delete:
- .git/ folder
- .githooks/ folder
- scripts/git-automation/ files

Cleanup safe:
Get-ChildItem writable/logs -Filter "*.log" |
    Where-Object {$_.LastWriteTime -lt (Get-Date).AddDays(-30)} |
    Remove-Item
```

---

## 🎯 Advanced Troubleshooting

### Debugging Script Execution

```powershell
# Enable debug output
$DebugPreference = "Continue"

# Run with debug info
.\scripts\git-automation\auto-commit.ps1 -Debug

# Shows detailed step-by-step execution
```

### Manual Hook Testing

```powershell
# Test pre-commit hook
Write-Host "Testing pre-commit..."
& ".\scripts\git-automation\pre-commit.ps1"

# Test commit-msg hook
Write-Host "Testing commit-msg..."
& ".\scripts\git-automation\commit-msg.ps1" ".git/COMMIT_EDITMSG"

# Test pre-push hook
Write-Host "Testing pre-push..."
& ".\scripts\git-automation\pre-push.ps1"
```

### Check Actual Error Log

```powershell
# View full log with errors
Get-Content "writable/logs/git-automation/git-automation-$(Get-Date -Format 'yyyy-MM-dd').log"

# Filter untuk errors only
Select-String "ERROR" "writable/logs/git-automation/git-automation-*.log"

# Get last 100 lines
Get-Content "writable/logs/git-automation/git-automation-$(Get-Date -Format 'yyyy-MM-dd').log" -Tail 100
```

### Reset Automation State

```powershell
# If system stuck atau corrupt:

# 1. Remove automation
.\scripts\git-automation\setup.ps1 -Uninstall

# 2. Clean slate
git reset --hard origin/main

# 3. Reinstall
.\scripts\git-automation\setup.ps1

# 4. Verify
git commit --allow-empty -m "test: reset and verify"
```

---

## 🚨 Emergency Recovery

### Worst Case: Total Reset

```powershell
# HATI-HATI: This nukes everything local!

# 1. Backup current work
Copy-Item . ".\backup-$(Get-Date -Format 'yyyy-MM-dd-HHmmss')" -Recurse

# 2. Clean git
git reset --hard HEAD~100              # Undo many commits
git fetch origin
git reset --hard origin/main           # Match remote exactly

# 3. Remove automation
Remove-Item .githooks -Recurse
git config --unset core.hooksPath

# 4. Reinstall
.\scripts\git-automation\setup.ps1

# 5. Verify
git status
```

### Recover Lost Commits

```powershell
# Git bisa recover commits sampai ~30 hari

# See all commits (including "deleted"):
git reflog

# Output:
# abc123 (HEAD@{0}): reset: moving to HEAD~1
# def456 (HEAD@{1}): commit: feat: something
# ghi789 (HEAD@{2}): commit: fix: bug

# Restore lost commit:
git reset --hard def456

# Now commit def456 restored!
```

### Nuclear Option: Clone Fresh

```powershell
# Jika semua rusak & tidak bisa diperbaiki:

# 1. Backup any local changes
mkdir backup
Copy-Item *.txt backup  # backup your work files

# 2. Move away from project
cd ..
Rename-Item monika monika-old

# 3. Clone fresh
git clone https://github.com/nanangpx0-hub/monika.git

# 4. Setup automation
cd monika
.\scripts\git-automation\setup.ps1

# 5. Re-apply your changes
# Copy backup files back, stage, commit
```

---

## 📞 Getting Help

### Information to Collect Before Asking

```
When requesting help, provide:

1. Error message (exact copy)
   - Screenshot atau log excerpt

2. What you were doing
   - Command yang Anda jalankan
   - Files yang sedang dikerjakan

3. System info
   git --version
   $PSVersionTable.PSVersion
   php -v

4. Recent logs
   Get-Content writable/logs/git-automation/git-automation-*.log -Tail 50

5. Git status
   git status
   git log --oneline -5

6. Remote info
   git remote -v
```

### Resources

| Resource | Link |
|----------|------|
| Main Documentation | docs/GIT_AUTOMATION_GUIDE.md |
| Quick Start | docs/GIT_AUTOMATION_QUICKSTART.md |
| Setup Guide | docs/GIT_AUTOMATION_SETUP_LENGKAP.md |
| Git Official | https://git-scm.com/doc |
| GitHub Help | https://docs.github.com |

---

## 📊 Diagnostics Report

```powershell
# Run this untuk generate diagnostics report

$report = @{
    DateTime = Get-Date
    GitVersion = & git --version
    PowerShellVersion = $PSVersionTable.PSVersion
    PHPVersion = & php -v
    HooksPath = & git config core.hooksPath
    ScriptsExist = (Get-ChildItem "scripts/git-automation" -Filter "*.ps1" | Measure).Count
    LogsExist = Test-Path "writable/logs/git-automation"
    LastCommit = & git log --oneline -1
    RemoteURL = & git remote -v
}

$report | Format-List
# Save to file:
$report | Out-File "diagnostics-$(Get-Date -Format 'yyyy-MM-dd-HHmmss').txt"
```

---

**Need More Help? 🆘**

1. Check [README.md](README.md) untuk overview
2. See [GIT_AUTOMATION_GUIDE.md](GIT_AUTOMATION_GUIDE.md) untuk lengkap documentation
3. Search pada halaman ini untuk error message Anda
4. Provide diagnostics info dan share error log

**Happy Troubleshooting! 🔧**
