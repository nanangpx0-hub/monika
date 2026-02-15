# MONIKA Git Automation - Quick Start Guide

**For impatient developers who just want to get started** ⚡

## 5-Minute Setup

### 1. Open PowerShell as Administrator

```powershell
# Right-click PowerShell → "Run as Administrator"
```

### 2. Navigate to Project

```powershell
cd E:\laragon\www\monika
```

### 3. Install (One Command!)

```powershell
powershell -ExecutionPolicy Bypass -File scripts/git-automation/setup.ps1
```

**Expected Result:**
```
✓ All git hooks installed successfully!
```

**Done!** Your Git automation is now active.

---

## Your First Commit (30 Seconds)

```powershell
# 1. Make changes
# ... edit your code file ...

# 2. Stage changes
git add app/Controllers/Dashboard.php

# 3. Commit (hooks run automatically!)
git commit -m "feat(dashboard): add new widget"

# Success! Changes are pushed automatically.
```

---

## Commit Message Cheat Sheet

Quick reference for commit messages:

```
# New feature
git commit -m "feat: add two-factor authentication"
git commit -m "feat(auth): implement 2FA support"

# Bug fix
git commit -m "fix: resolve login timeout"
git commit -m "fix(auth): handle session expiry correctly"

# Documentation
git commit -m "docs: update setup guide"
git commit -m "docs(readme): add installation steps"

# Maintenance
git commit -m "chore: update dependencies"
git commit -m "chore(build): upgrade CodeIgniter to v4.8"

# Refactoring
git commit -m "refactor: optimize database queries"
git commit -m "refactor(models): simplify logic"

# Tests
git commit -m "test: add authentication tests"

# Performance
git commit -m "perf: reduce dashboard load time"

# Styling/Formatting
git commit -m "style: fix code indentation"

# CI/CD
git commit -m "ci: add GitHub Actions workflow"
```

---

## What Happens Automatically?

### Before Commit
✓ Checks PHP syntax  
✓ Scans for security issues  
✓ Validates code indentation  
✓ Runs unit tests (if enabled)  

### Commit Message
✓ Validates format  
✓ Auto-fixes common mistakes  
✓ Ensures consistency  

### Before Push
✓ Creates safety checkpoint  
✓ Attempts push (retry 3 times if fails)  
✓ Rolls back if push fails  

### Logging
✓ Every operation logged  
✓ Logs in: `writable/logs/git-automation/`  
✓ Errors clearly marked  

---

## Too Long? Didn't Read (TL;DR)

```powershell
# Setup (one-time)
powershell -ExecutionPolicy Bypass -File scripts/git-automation/setup.ps1

# Your daily workflow
git add <file>
git commit -m "feat: description"
# ← Automatically pushed!

# View logs if something breaks
Get-Content writable/logs/git-automation/git-automation-$(Get-Date -Format 'yyyy-MM-dd').log -Tail 50
```

---

## Troubleshooting (Common Issues)

### Issue: "PowerShell execution policy" error

```powershell
# Fix: Run as Administrator, then:
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### Issue: Hooks not running

```powershell
# Fix: Check installation
Get-ChildItem .git/hooks/ -Filter *.cmd

# Should see: pre-commit.cmd, commit-msg.cmd, pre-push.cmd
```

### Issue: "Command not found" errors

```powershell
# Fix: Make sure you're in the monika directory
cd E:\laragon\www\monika
git status
```

### Issue: Commit blocked by quality checks

```powershell
# Check what's wrong
php -l app/Controllers/YourFile.php

# Fix the error, then re-commit
git add app/Controllers/YourFile.php
git commit -m "fix: resolve syntax error"
```

---

## Need More Help?

📖 Full Documentation: [docs/GIT_AUTOMATION_GUIDE.md](GIT_AUTOMATION_GUIDE.md)

**In there you'll find:**
- Complete setup instructions
- Detailed workflow explanations
- All configuration options
- Advanced troubleshooting
- Best practices
- Real-world examples

---

## Environment Info

- **Framework**: CodeIgniter 4
- **Git**: 2.40.1+ required
- **PowerShell**: 5.0+ (Windows) or Core (macOS/Linux)
- **PHP**: 8.2+ (for code quality checks)
- **Repository**: git@github.com:nanangpx0-hub/monika.git

---

✨ **Happy coding!** Your commits are now automatically validated and pushed.
