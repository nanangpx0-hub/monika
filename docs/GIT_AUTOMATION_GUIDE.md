# MONIKA Git Automation System - Complete Documentation

**Last Updated**: February 15, 2026  
**Version**: 1.0  
**Status**: Production Ready

---

## TABLE OF CONTENTS

1. [Overview](#overview)
2. [Architecture](#architecture)
3. [Installation & Setup](#installation--setup)
4. [Usage Guide](#usage-guide)
5. [Configuration](#configuration)
6. [Git Hooks Explained](#git-hooks-explained)
7. [Commit Message Format](#commit-message-format)
8. [Error Handling & Recovery](#error-handling--recovery)
9. [Troubleshooting](#troubleshooting)
10. [FAQ & Best Practices](#faq--best-practices)
11. [Glossary](#glossary)

---

## OVERVIEW

### Purpose

The MONIKA Git Automation System provides **automated, reliable, and intelligent Git operations** for the MONIKA project. It ensures code quality, maintains commit message standards, and safely pushes changes to GitHub with built-in error recovery.

### Key Features

| Feature | Description |
|---------|-------------|
| **Automated Push** | Commits are automatically pushed after passing validation |
| **Code Quality Checks** | PHP syntax validation and security scanning |
| **Unit Tests** | Automatic test execution before commit (when enabled) |
| **Smart Commit Messages** | Auto-fixes and validates commit message format |
| **Retry Mechanism** | Exponential backoff retry for network issues |
| **Automatic Rollback** | Reverts to checkpoint if push fails |
| **Comprehensive Logging** | Detailed logs for troubleshooting |
| **Status Notifications** | Real-time notifications of operation status |
| **Security Scanning** | Detects common security vulnerabilities |

### Workflow Diagram

```
┌─────────────────────┐
│   Make Changes      │
│   to Code Files     │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  $ git add <file>   │
└──────────┬──────────┘
           │
           ▼
┌──────────────────────────────┐
│  [PRE-COMMIT HOOK]           │
│  • Code quality checks       │
│  • PHP syntax validation     │
│  • Security scanning         │
│  • Unit tests (optional)     │
└──────────┬───────────────────┘
           │
           ▼ (checks pass)
┌─────────────────────┐
│  $ git commit       │
└──────────┬──────────┘
           │
           ▼
┌──────────────────────────────┐
│  [COMMIT-MSG HOOK]           │
│  • Validate message format   │
│  • Auto-fix if possible      │
│  • Check commit type         │
└──────────┬───────────────────┘
           │
           ▼ (valid message)
┌─────────────────────┐
│  $ git push         │
└──────────┬──────────┘
           │
           ▼
┌──────────────────────────────┐
│  [PRE-PUSH HOOK]             │
│  • Create checkpoint         │
│  • Verify branch setup       │
│  • Execute push with retry   │
└──────────┬───────────────────┘
           │
      ┌────┴────┐
      │          │
   (OK)         (FAIL)
      │          │
      ▼          ▼
   [Push OK]  [Rollback]
```

---

## ARCHITECTURE

### Directory Structure

```
monika/
├── .git/
│   └── hooks/                          # Git hooks installed here
│       ├── pre-commit.cmd              # Code quality checks
│       ├── commit-msg.cmd              # Message validation
│       └── pre-push.cmd                # Push operations
│
├── .githooks/                          # (Legacy reference)
│
├── scripts/
│   └── git-automation/
│       ├── config.ps1                  # Configuration (EDIT THIS)
│       ├── utils.ps1                   # Shared utilities
│       ├── setup.ps1                   # Installation script
│       ├── auto-commit.ps1             # Auto-commit utility
│       ├── pre-commit.ps1              # Pre-commit hook logic
│       ├── commit-msg.ps1              # Commit message handler
│       └── pre-push.ps1                # Pre-push hook logic
│
└── writable/
    └── logs/
        └── git-automation/
            ├── git-automation-YYYY-MM-DD.log    # Daily logs
            └── push_history.json                 # Push history
```

### Component Overview

| Component | Purpose | Responsibility |
|-----------|---------|-----------------|
| **config.ps1** | Central configuration | Defines behavior, retry limits, notifications |
| **utils.ps1** | Shared utilities | Logging, Git operations, retry logic, rollback |
| **setup.ps1** | Installation | Installs hooks, configures Git, creates templates |
| **auto-commit.ps1** | Manual trigger | Auto-stages, commits, and pushes changes |
| **pre-commit.ps1** | Hook script | Validates code quality before commit |
| **commit-msg.ps1** | Hook script | Validates and fixes commit message format |
| **pre-push.ps1** | Hook script | Ensures push reliability with retry |

---

## INSTALLATION & SETUP

### Prerequisites

- **Git 2.0+** installed and configured
- **PowerShell 5.0+** (Windows) or PowerShell Core (macOS/Linux)
- **PHP 8.2+** with CLI installed (for code quality checks)
- Write permissions to `.git/` directory

### Step-by-Step Installation

#### 1. Navigate to Project Directory

```powershell
cd E:\laragon\www\monika
```

#### 2. Run Setup Script

```powershell
powershell -ExecutionPolicy Bypass -File scripts/git-automation/setup.ps1
```

**Expected Output:**
```
╔════════════════════════════════════════════════════════════════════╗
║     MONIKA Git Automation - Setup & Installation                  ║
╚════════════════════════════════════════════════════════════════════╝

Project Root: E:\laragon\www\monika
Git Hooks Dir: E:\laragon\www\monika\.git\hooks

Installing Git Hooks...
  ✓ Installed: pre-commit
  ✓ Installed: commit-msg
  ✓ Installed: pre-push
  ✓ Set core.hooksPath
  ✓ Set commit.template
  ✓ Created commit template

✓ All git hooks installed successfully!
```

#### 3. Verify Installation

```powershell
# Check if hooks are installed
Get-Item .git/hooks/*.cmd | Format-Table Name, Length

# Check Git configuration
git config --list | findstr hooks
```

#### 4. Configure Settings (Optional)

Edit `scripts/git-automation/config.ps1` to customize:

```powershell
# Example: Enable unit tests
$ENABLE_UNIT_TESTS = $true

# Example: Disable notifications
$ENABLE_NOTIFICATIONS = $false

# Example: Set custom retry attempts
$MAX_RETRY_ATTEMPTS = 5
```

### Uninstallation

To remove Git automation:

```powershell
powershell -ExecutionPolicy Bypass -File scripts/git-automation/setup.ps1 -Uninstall
```

---

## USAGE GUIDE

### Workflow 1: Standard Git Commit (With Hooks)

```powershell
# 1. Make changes to files
# ... edit your code ...

# 2. Stage changes
git add app/Controllers/Dashboard.php
git add app/Models/DokumenModel.php

# 3. Commit (hooks automatically run)
git commit -m "feat(dashboard): add real-time monitoring"
# → Pre-commit hook validates code
# → Commit-msg hook validates message format
# → Pre-push hook handles push with retry

# Done! Changes are automatically pushed to GitHub
```

### Workflow 2: Automatic Commit (Auto-Commit Script)

The `auto-commit.ps1` script automatically stages, commits, and pushes all changes:

```powershell
# Auto-commit with auto-generated message
.\scripts\git-automation\auto-commit.ps1

# Auto-commit with custom message
.\scripts\git-automation\auto-commit.ps1 -CustomMessage "feat(auth): implement two-factor authentication"

# Dry run (shows what would happen without making changes)
.\scripts\git-automation\auto-commit.ps1 -DryRun

# Skip quality checks (use with caution!)
.\scripts\git-automation\auto-commit.ps1 -SkipTests
```

### Workflow 3: View Logs

```powershell
# View today's Git automation log
Get-Content writable/logs/git-automation/git-automation-$(Get-Date -Format 'yyyy-MM-dd').log

# Watch logs in real-time
Get-Content -Path writable/logs/git-automation/git-automation-$(Get-Date -Format 'yyyy-MM-dd').log -Wait

# View push history
Get-Content writable/logs/git-automation/push_history.json | ConvertFrom-Json
```

---

## CONFIGURATION

### config.ps1 Settings

#### Path Configuration

```powershell
$LOG_FILE = "writable/logs/git-automation/git-automation-$(Get-Date -Format 'yyyy-MM-dd').log"
$LOGS_DIR = "writable/logs/git-automation"
$TEMP_DIR = "writable/git-temp"
```

**Change these if:**
- You want logs stored elsewhere
- You're using a different directory structure

#### Commit Message Settings

```powershell
$COMMIT_PREFIXES = @("feat", "fix", "chore", "refactor", "docs", "style", "test", "perf", "ci", "build")
```

**Allowed Prefixes:**
- `feat` - New feature
- `fix` - Bug fix
- `chore` - Maintenance task
- `refactor` - Code refactoring
- `docs` - Documentation changes
- `style` - Formatting/style changes
- `test` - Test additions/changes
- `perf` - Performance improvements
- `ci` - CI/CD configuration
- `build` - Build system changes

#### Retry Configuration

```powershell
$MAX_RETRY_ATTEMPTS = 3              # Try 3 times max
$RETRY_DELAY_SECONDS = 5             # Wait 5 sec between attempts
$RETRY_BACKOFF_MULTIPLIER = 1.5      # Increase wait time by 50% each attempt
```

**Retry Timeline Example:**
```
Attempt 1: Immediate
Attempt 2: 5 seconds delay
Attempt 3: 7.5 seconds delay (5 × 1.5)
Attempt 4: 11.25 seconds delay (7.5 × 1.5)
```

#### Code Quality Settings

```powershell
$ENABLE_CODE_QUALITY = $true         # Check PHP syntax
$ENABLE_UNIT_TESTS = $false          # Don't require tests (set to $true when ready)
```

#### Notification Settings

```powershell
$ENABLE_NOTIFICATIONS = $true        # Show notifications
$NOTIFY_SUCCESS = $true              # Notify on success
$NOTIFY_ERROR = $true                # Notify on error
```

---

## GIT HOOKS EXPLAINED

### PRE-COMMIT HOOK

**Location**: `scripts/git-automation/pre-commit.ps1`  
**Trigger**: Before `git commit` is finalized  
**Purpose**: Validate code before it's committed

**Checks Performed:**

1. **Code Quality Analysis**
   - PHP syntax validation using `php -l`
   - Detects parse errors, invalid syntax
   
2. **Security Scanning**
   - Detects dynamic code execution: `eval()`, `exec()`, `system()` with variables
   - Identifies security-related TODOs/FIXMEs
   
3. **File Size Validation**
   - Warns if files exceed 10 MB
   - Prevents large binary files in repository

4. **Unit Tests** (if enabled)
   - Runs `php spark test`
   - Blocks commit if tests fail

**Exit Codes:**
- `0` = All checks passed, ready to commit
- `1` = Check failed, commit blocked

**Example Output:**
```
[2026-02-15 10:30:45] [INFO] ======= PRE-COMMIT HOOK: Running pre-commit checks =======
[2026-02-15 10:30:45] [INFO] Found 2 staged file(s) to check
[2026-02-15 10:30:45] [INFO] Running code quality checks...
[2026-02-15 10:30:45] [DEBUG] No PHP files to check
[2026-02-15 10:30:45] [INFO] Checking for security issues...
[2026-02-15 10:30:45] [INFO] All pre-commit checks passed!
[2026-02-15 10:30:45] [SUCCESS] All pre-commit checks passed!
```

### COMMIT-MSG HOOK

**Location**: `scripts/git-automation/commit-msg.ps1`  
**Trigger**: After user enters commit message  
**Purpose**: Validate and auto-fix commit message format

**Validation Rules:**

1. **Prefix Validation**
   ```
   ✓ Correct:   feat: add new dashboard
   ✓ Correct:   fix(auth): handle login error
   ✗ Wrong:     Added new feature
   ✗ Wrong:     bugfix: something
   ```

2. **Auto-Fix Capability**
   - `bugfix:` → `fix:`
   - `feature:` → `feat:`
   - Missing prefix → `chore:` (default)

3. **First Line Length**
   - Warning if > 72 characters
   - Recommended: < 50 characters

4. **Body Formatting**
   - Ensures blank line after header
   - Auto-adds if missing

**Example Transformation:**
```
BEFORE: "Added new monitoring feature to dashboard"
AFTER:  "feat(dashboard): add new monitoring feature"
```

### PRE-PUSH HOOK

**Location**: `scripts/git-automation/pre-push.ps1`  
**Trigger**: Before `git push` sends to remote  
**Purpose**: Ensure safe, reliable push with recovery

**Operations:**

1. **Checkpoint Creation**
   - Saves current commit SHA
   - Used for rollback if push fails

2. **Validation Checks**
   - Verifies branch exists (or sets upstream)
   - Confirms commits to push

3. **Push with Retry**
   - Attempts push up to 3 times
   - Exponential backoff between retries
   - Network error recovery

4. **Automatic Rollback** (on failure)
   - Reverts to checkpoint if push fails
   - Cleans up uncommitted changes
   - Logs failure details

**Retry Mechanism:**
```
Attempt 1: git push (immediate)
   ↓ [Network Error]
Wait 5 seconds
Attempt 2: git push (retry)
   ↓ [Network Error]
Wait 7.5 seconds
Attempt 3: git push (final attempt)
   ↓ [Failed]
ROLLBACK: revert to checkpoint
```

---

## COMMIT MESSAGE FORMAT

### Standard Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Components Explained

#### Type (Required)

Describes the nature of changes:

| Type | Usage | Example |
|------|-------|---------|
| `feat` | New feature | `feat: add user authentication` |
| `fix` | Bug fix | `fix: resolve login timeout issue` |
| `chore` | Maintenance | `chore: update dependencies` |
| `refactor` | Code restructure | `refactor: simplify dashboard logic` |
| `docs` | Documentation | `docs: update README with setup guide` |
| `style` | Formatting | `style: fix indentation in auth.php` |
| `test` | Test changes | `test: add authentication tests` |
| `perf` | Performance | `perf: optimize database queries` |
| `ci` | CI/CD config | `ci: add GitHub Actions workflow` |
| `build` | Build config | `build: update composer dependencies` |

#### Scope (Optional)

Indicates affected area:

```
feat(dashboard): add real-time widget
feat(auth): implement two-factor authentication
fix(models): resolve database migration issue
```

Common scopes:
- `dashboard` - Dashboard module
- `auth` - Authentication module
- `models` - Data models
- `controllers` - Controllers
- `views` - View templates
- `database` - Database & migrations
- `config` - Configuration

#### Subject (Required)

Brief description of changes:

✓ **Good:**
- "add user authentication system"
- "fix login validation error"
- "update documentation"

✗ **Bad:**
- "Fixed bugs" (too vague)
- "MONIKA" (unclear)
- "Added code" (too generic)

**Guidelines:**
- Use imperative mood (add, fix, update, not added/fixed/updated)
- Lowercase first letter
- No period at end
- < 50 characters recommended

#### Body (Optional)

Detailed explanation of changes:

```
feat(monitoring): add anomaly ranking widget

The new widget displays Field Enumerators (PCL) ranked by
error count. This helps supervisors quickly identify
employees needing additional training.

Key changes:
- New query method getPclPerformance()
- Dashboard widget layout modifications
- Added sorting by error count

Performance impact: < 100ms additional load time
```

#### Footer (Optional)

References and metadata:

```
Closes #123
Related-To: #456
Reviewed-By: @nanangpx0-hub
```

### Real-World Examples

**Example 1: New Feature**
```
feat(kegiatan): add master activity management

Users can now create and manage survey activities. Each activity
has start/end dates and active status. Documents are linked to
activities for proper segregation.

Closes #42
```

**Example 2: Bug Fix**
```
fix(dokumen): resolve NULL pointer in status update

Fixed issue where updating document status to 'Valid' would fail
if anomaly_log had no entries. Added null coalescing check.

Closes #89
```

**Example 3: Chore**
```
chore(build): update CodeIgniter 4 to version 4.8.0

Updated composer dependencies. No breaking changes to existing code.

Tested on: PHP 8.2, Windows 11 Laragon
```

---

## ERROR HANDLING & RECOVERY

### System Architecture

The Git automation system has multiple layers of error handling:

```
┌─────────────────────────────────────────┐
│   Layer 1: Validation & Prevention      │
│   • Pre-commit checks                   │
│   • Commit-msg validation               │
│   • File filtering                      │
└──────────────┬──────────────────────────┘
               ↓ (errors prevented)
┌─────────────────────────────────────────┐
│   Layer 2: Retry Mechanism              │
│   • Network retry with backoff          │
│   • Exponential delay increase          │
│   • Max 3 attempts                      │
└──────────────┬──────────────────────────┘
               ↓ (retry exhausted)
┌─────────────────────────────────────────┐
│   Layer 3: Checkpoint & Rollback        │
│   • Save commit SHA before push         │
│   • Hard reset on failure               │
│   • Clean unstaged changes              │
└──────────────┬──────────────────────────┘
               ↓ (graceful degradation)
┌─────────────────────────────────────────┐
│   Layer 4: Logging & Notifications      │
│   • Detailed error logs                 │
│   • Push history tracking               │
│   • User notifications                  │
└─────────────────────────────────────────┘
```

### Common Errors & Solutions

#### Error 1: "Failed to verify Git config"

**Message:**
```
[ERROR] Not in a Git repository
```

**Cause:** Running script outside Git repo

**Solution:**
```powershell
cd E:\laragon\www\monika
git status  # Verify you're in a repo
```

---

#### Error 2: "Code quality checks failed"

**Message:**
```
[ERROR] PHP syntax error in app/Controllers/Dashboard.php
```

**Cause:** Invalid PHP syntax in modified file

**Solution:**
```powershell
# Check syntax manually
php -l app/Controllers/Dashboard.php

# Fix the error, then re-stage
git add app/Controllers/Dashboard.php
git commit -m "fix: resolve PHP syntax error"
```

---

#### Error 3: "Push failed after 3 attempts"

**Message:**
```
[ERROR] Push failed after 3 attempts: fatal: could not read Username...
```

**Cause:** Network issue or authentication failure

**Solution:**
```powershell
# Option 1: Check internet connection
ping github.com

# Option 2: Verify Git credentials
git config user.name
git config user.email

# Option 3: Manual push after system recoverable
git push origin main

# Option 4: Check Git authentication
git credential-manager approve  # (Windows)
```

---

#### Error 4: "Commit message format invalid"

**Message:**
```
[ERROR] Commit message doesn't follow format: 'prefix: description'
```

**Cause:** Commit message doesn't match pattern

**Solution:**
```powershell
# Correct format examples:
# ✓ feat: add new feature
# ✓ fix(auth): resolve login error
# ✗ Added new feature (missing prefix)
# ✗ feat fix: double prefix

# Amend last commit with correct message
git commit --amend -m "feat: correct description"
```

---

#### Error 5: "Unit tests failed"

**Message:**
```
[ERROR] Unit tests failed with exit code: 1
```

**Cause:** Test failures block commit

**Solution:**
```powershell
# Run tests manually to see details
php spark test

# Fix code, re-run tests
php spark test

# When tests pass, try committing again
git add .
git commit -m "fix: make tests pass"
```

---

### Manual Recovery Procedures

#### Scenario 1: Stuck in Pre-Commit Loop

If hooks keep blocking commits:

```powershell
# 1. Disable hooks temporarily
git config core.hooksPath ""

# 2. Commit with message
git commit -m "fix: resolve issue"

# 3. Fix the issue
git push origin main

# 4. Re-enable hooks
git config core.hooksPath ".githooks"
```

#### Scenario 2: Rollback After Failed Push

If push failed and was rolled back:

```powershell
# 1. Check current state
git log --oneline -5
git status

# 2. See unstaged changes
git diff

# 3. Re-commit if desired
git add .
git commit -m "chore: reapply after rollback"
git push origin main
```

#### Scenario 3: Recover from Partial Rollback

If rollback was incomplete:

```powershell
# 1. Check Git reflog for previous state
git reflog

# 2. Restore to specific point
git reset --hard <commit-sha>

# 3. Verify state
git log --oneline -5
```

---

## TROUBLESHOOTING

### Diagnostic Tools

#### Check Hook Installation

```powershell
# List installed hooks
Get-ChildItem .git/hooks/ -Filter *.cmd

# Test hook directly
& .git/hooks/pre-commit.cmd

# Verify hook content
Get-Content .git/hooks/pre-commit.cmd
```

#### Check Configuration

```powershell
# View all settings
.\scripts\git-automation\config.ps1

# Check Git config
git config --list | findstr monika

# Check execution policy
Get-ExecutionPolicy
```

#### Review Logs

```powershell
# Today's logs
$logFile = "writable/logs/git-automation/git-automation-$(Get-Date -Format 'yyyy-MM-dd').log"
Get-Content $logFile

# Last 50 lines
Get-Content $logFile -Tail 50

# Search for errors
Select-String -Path $logFile -Pattern "ERROR"

# Follow log in real-time
Get-Content $logFile -Wait
```

### Common Issues & Fixes

| Issue | Cause | Fix |
|-------|-------|-----|
| Hooks not running | Execution policy too strict | `Set-ExecutionPolicy -ExecutionPolicy RemoteSigned` |
| "Permission denied" | File permissions issue | `chmod +x scripts/git-automation/*.ps1` |
| PowerShell not found | PowerShell not in PATH | Add PowerShell to PATH or use full path |
| Large file rejection | File > 10 MB | Use `.gitignore` or Git LFS |
| "hook declined" message | Hook returned exit code 1 | Check logs for specific issue |

### Enable Debug Mode

To get more detailed logging:

```powershell
# Edit config.ps1
$LOG_LEVEL = "DEBUG"

# Re-run operations - detailed logs now generated
git add app/Models/Test.php
git commit -m "test: debug mode"
```

**Debug output includes:**
- Full command output
- Internal variable values
- File paths being checked
- Retry attempt details

---

## FAQ & BEST PRACTICES

### Frequently Asked Questions

**Q: Can I disable hooks for a single commit?**

A: Yes, but not recommended:
```powershell
# Bypass pre-commit hook only
git commit --no-verify -m "chore: quick fix"

# This still runs commit-msg and pre-push hooks
# Use sparingly for emergencies only
```

**Q: How do I include multiple files in one commit?**

A:
```powershell
# Stage multiple files
git add app/Models/*.php
git add app/Controllers/Dashboard.php

# Or stage all changes
git add .

# Commit once
git commit -m "feat: multiple components"
```

**Q: Can I use different commit message format per repository?**

A: Yes, modify `$COMMIT_PREFIXES` in config.ps1:
```powershell
# For MONIKA specifically
$COMMIT_PREFIXES = @("feat", "fix", "chore", "refactor", "docs", "style", "test", "perf", "ci", "build", "monika")
```

**Q: How often should I check logs?**

A: Logs are created daily. Check after:
- Any failed operation
- System errors
- After first successful setup
- Weekly maintenance

**Q: What if push succeeds but I didn't want it?**

A: Push is permanent. To undo:
```powershell
# Force revert the commit on remote (DANGEROUS!)
git revert <commit-sha>
git push

# Or reset local + force push (only if you're sure)
git reset --hard HEAD~1
git push --force
```

---

### Best Practices

#### 1. Commit Frequently, Push Regularly

```powershell
# ✓ Good: Commit after each logical change
git add app/Models/UserModel.php
git commit -m "feat(models): add getUserById method"

# ✗ Bad: Wait until end of day with huge commit
git add .
git commit -m "chore: end of day changes"
```

#### 2. Write Descriptive Commit Messages

```powershell
# ✓ Good: Someone can understand what/why
git commit -m "fix(auth): resolve session expiry on page reload by extending timeout to 30min"

# ✗ Bad: Vague and unhelpful
git commit -m "fix: stuff"
```

#### 3. One Feature per Commit

```powershell
# ✓ Good: Separate concerns
git add app/Controllers/Auth.php
git commit -m "feat(auth): implement login functionality"
git add app/Views/auth/login.php
git commit -m "feat(views): add login form template"

# ✗ Bad: Mixing unrelated changes
git add .
git commit -m "chore: login stuff and dashboard updates"
```

#### 4. Review Changes Before Committing

```powershell
# See what's changed
git diff

# See what's staged
git diff --cached

# Then commit with confidence
git commit -m "fix: resolved issue"
```

#### 5. Use Meaningful Scopes

```powershell
# ✓ Good: Clear what area changed
git commit -m "feat(dashboard): add anomaly ranking"
git commit -m "fix(presensi): resolve date picker bug"

# ✗ Bad: Vague scope
git commit -m "feat(system): various fixes"
```

#### 6. Keep Commits Atomic

Each commit should be independently deployable:

```powershell
# ✓ Good: Logical unit
# Commit 1: feat(models): add DokumenModel methods
# Commit 2: feat(controllers): implement Dokumen operations
# Commit 3: feat(views): add dokumen list template

# ✗ Bad: Not deployable independently
# Commit: Added models, controllers, views for dokumen (all in one)
```

#### 7. Use Auto-Commit for Bulk Changes

```powershell
# When you have many minor changes
.\scripts\git-automation\auto-commit.ps1

# For production deployments or major features
git add app/
git add docs/
git commit -m "feat: implement major feature X"
git push
```

---

## GLOSSARY

### Terms

| Term | Definition |
|------|-----------|
| **Hook** | Script executed by Git at specific points in workflow |
| **Pre-commit** | Hook runs before commit is finalized |
| **Commit-msg** | Hook runs after message entered, before commit |
| **Pre-push** | Hook runs before push sends to remote |
| **Checkpoint** | Saved commit SHA for rollback purposes |
| **Rollback** | Revert to previous state after failure |
| **Retry** | Attempt operation again after failure |
| **Exponential Backoff** | Increasing wait time between retry attempts |
| **Staging** | Preparing files for commit with `git add` |
| **Remote** | GitHub repository (origin is default) |
| **Upstream** | Connection between local branch and remote |
| **Scope** | Area/component affected by change |
| **Prefix** | Type indicator (feat, fix, chore, etc.) |

### Exit Codes

| Code | Meaning |
|------|---------|
| `0` | Success - operation completed |
| `1` | Failure - operation blocked/failed |
| `2` | Invalid arguments |
| `127` | Command not found |

---

## Quick Reference

### Installation (30 seconds)

```powershell
cd E:\laragon\www\monika
powershell -ExecutionPolicy Bypass -File scripts/git-automation/setup.ps1
```

### Daily Workflow

```powershell
# Edit code
# ...

# Stage and commit
git add app/Controllers/Dashboard.php
git commit -m "feat(dashboard): add widget"

# Automatically pushed by pre-push hook!
```

### View Logs

```powershell
Get-Content writable/logs/git-automation/git-automation-$(Get-Date -Format 'yyyy-MM-dd').log -Tail 50
```

### Uninstall

```powershell
powershell -ExecutionPolicy Bypass -File scripts/git-automation/setup.ps1 -Uninstall
```

---

## Support & Feedback

- **Issues?** Check [Troubleshooting](#troubleshooting) section
- **Logs Location:** `writable/logs/git-automation/`
- **Config File:** `scripts/git-automation/config.ps1`
- **Repository:** https://github.com/nanangpx0-hub/monika.git

---

**Document Version**: 1.0  
**Last Updated**: February 15, 2026  
**Status**: Production Ready
