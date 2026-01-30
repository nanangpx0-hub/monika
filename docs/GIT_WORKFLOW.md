# Git Workflow dan Guidelines untuk MONIKA

Dokumentasi ini menjelaskan workflow Git, konvensi commit, dan best practices untuk project MONIKA.

## 📋 Daftar Isi

1. [Setup Initial](#setup-initial)
2. [Branching Strategy](#branching-strategy)
3. [Commit Conventions](#commit-conventions)
4. [Workflow Development](#workflow-development)
5. [Pulling & Pushing](#pulling--pushing)
6. [Merge & Pull Requests](#merge--pull-requests)
7. [Common Commands](#common-commands)
8. [Troubleshooting](#troubleshooting)

---

## Setup Initial

### Clone Repository

```bash
# HTTPS (recommended untuk CI/CD)
git clone https://github.com/nanangpx0-hub/monika.git

# SSH (untuk developer dengan SSH keys)
git clone git@github.com:nanangpx0-hub/monika.git

# Masuk ke folder project
cd monika
```

### Configure User (First Time Only)

```bash
# Setup global (applies to all repositories)
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"

# Setup local (applies only to this repository)
git config user.name "Your Name"
git config user.email "your.email@example.com"

# Verify configuration
git config --list
```

### Install Dependencies

```bash
# Install Composer dependencies
composer install

# Copy environment file
cp env .env

# Setup database (adjust credentials in .env)
mysql -u root < monika_schema.sql

# Run initial seeder
php spark db:seed RolesSeeder
php spark db:seed UsersSeeder
```

---

## Branching Strategy

Kami menggunakan **Git Flow** dengan structure sebagai berikut:

### Main Branches

| Branch | Purpose | Created From | Merge To |
|--------|---------|--------------|----------|
| `main` | Production-ready code | - | - |
| `develop` | Development integration | - | `main` (via PR) |

### Supporting Branches

| Branch | Prefix | Example | Purpose |
|--------|--------|---------|---------|
| Feature | `feature/` | `feature/user-authentication` | Fitur baru |
| Bugfix | `bugfix/` | `bugfix/login-validation` | Perbaikan bug |
| Hotfix | `hotfix/` | `hotfix/password-reset-issue` | Patch production |
| Documentation | `docs/` | `docs/api-guide` | Dokumentasi |

### Branch Naming Conventions

- **Gunakan lowercase** - `feature/user-management` ✅ bukan `Feature/User-Management`
- **Gunakan kebab-case** - `feature/add-role-validation` ✅ bukan `add_role_validation`
- **Deskriptif dan singkat** - `feature/dokumen-upload` ✅ bukan `feature/x` atau `feature/add-new-file`
- **Sertakan issue number** (jika ada) - `feature/ISSUE-123-dokumen-upload`

---

## Commit Conventions

### Format Commit Message

Kami menggunakan **Conventional Commits** format:

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Type (Required)

| Type | Description | Example |
|------|-------------|---------|
| `feat` | Fitur baru | `feat(auth): add two-factor authentication` |
| `fix` | Bug fix | `fix(dokumen): resolve upload validation error` |
| `docs` | Documentation changes | `docs(readme): update installation steps` |
| `style` | Code style (tidak mengubah logic) | `style(models): format indentation` |
| `refactor` | Code refactoring | `refactor(controllers): extract validation logic` |
| `perf` | Performance improvement | `perf(database): add index on dokumen_survei` |
| `test` | Adding/updating tests | `test(dokumen): add upload validation tests` |
| `chore` | Build, dependencies, tools | `chore: update composer dependencies` |
| `ci` | CI/CD changes | `ci: add GitHub Actions workflow` |

### Scope (Optional but Recommended)

Scope menunjukkan area yang diubah:
- `auth` - Authentication related
- `dokumen` - Document management
- `kegiatan` - Activity management
- `laporan` - Reporting
- `monitoring` - Monitoring dashboard
- `database` - Database/migrations
- `models` - Model layer
- `controllers` - Controller layer
- `views` - View/frontend
- `config` - Configuration

### Subject (Required)

- **Maksimal 50 karakter**
- **Imperative mood** - gunakan "add" bukan "added" atau "adds"
- **Tidak perlu titik di akhir**
- **Dalam Bahasa Inggris** (untuk consistency dalam team internasional)
- **Lowercase** di awal

### Body (Optional for Non-Trivial Changes)

```
feat(dokumen): implement document status workflow

- Add 'Sudah Entry' status for processed documents
- Add 'Error' status for documents with anomalies
- Implement pernah_error flag to track corrections
- Create anomali_log entries for error tracking

This enables proper workflow management in the document
lifecycle from upload to final processing.
```

### Footer (Optional)

Gunakan untuk closing issues atau breaking changes:

```
fix(auth): resolve password verification bug

Fixes: #123
Closes: #125
Breaking Change: Session structure changed from array to object
```

### Commit Examples

**Good Commits:**
```
feat(auth): add login page with email/username support
fix(dokumen): correct file upload validation message
docs(api): add endpoint documentation for laporan endpoints
refactor(models): extract validation logic into separate method
test(kegiatan): add test cases for kegiatan CRUD operations
```

**Bad Commits:**
```
update files
bug fixes
fix stuff
added new feature
random changes
```

---

## Workflow Development

### Step 1: Create Feature Branch

```bash
# Update develop branch
git checkout develop
git pull origin develop

# Create feature branch
git checkout -b feature/user-authentication

# Or create and checkout in one command
git checkout -b feature/user-authentication develop
```

### Step 2: Make Changes & Commit

```bash
# Check status
git status

# Stage changes
git add app/Controllers/Auth.php
git add app/Models/UserModel.php

# Or stage all changes
git add .

# Commit with meaningful message
git commit -m "feat(auth): implement user authentication system

- Add login controller with email/username support
- Add password verification with bcrypt
- Implement session-based authentication
- Add login form view with validation
"
```

### Step 3: Push to Remote

```bash
# Push branch to GitHub
git push -u origin feature/user-authentication

# Subsequent pushes
git push
```

### Step 4: Create Pull Request (PR)

1. Buka https://github.com/nanangpx0-hub/monika
2. Klik **Pull requests** → **New pull request**
3. Atur:
   - **Base:** `develop` (or `main` untuk hotfix)
   - **Compare:** `feature/user-authentication`
4. Tulis PR description dengan format:
   ```markdown
   ## Description
   Menambahkan sistem autentikasi pengguna dengan session-based login.

   ## Changes
   - Add Auth controller dengan login/logout functionality
   - Add User model dengan password hashing
   - Add login form view
   - Add AuthFilter untuk protected routes

   ## Testing
   - [x] Login berhasil dengan username
   - [x] Login berhasil dengan email
   - [x] Password verification bekerja
   - [x] Session persists across requests

   ## Screenshots
   [Attach screenshots jika ada UI changes]

   Closes: #42
   ```
5. Klik **Create pull request**

---

## Pulling & Pushing

### Update Local Branch dari Remote

```bash
# Update current branch
git pull

# Equivalent to:
git fetch origin
git merge origin/current-branch

# Or use rebase untuk cleaner history
git pull --rebase
```

### Push Changes ke Remote

```bash
# Push current branch
git push

# Push specific branch
git push origin feature/user-authentication

# Push dengan tracking (first time)
git push -u origin feature/user-authentication

# Force push (gunakan HANYA jika necessary dan anda tahu risikonya)
git push --force-with-lease origin feature/user-authentication
```

### Sync Fork dengan Upstream (Jika bekerja dari fork)

```bash
# Add upstream remote
git remote add upstream https://github.com/nanangpx0-hub/monika.git

# Fetch upstream changes
git fetch upstream

# Merge upstream develop ke local develop
git checkout develop
git merge upstream/develop

# Push to your fork
git push origin develop
```

---

## Merge & Pull Requests

### Code Review Checklist

Sebelum merge, pastikan:

- ✅ **Functionality** - Fitur bekerja sesuai requirements
- ✅ **Code Quality** - Code readable dan mengikuti conventions
- ✅ **Tests** - Unit tests sudah dibuat (target: >70% coverage)
- ✅ **Documentation** - Code documented dan doc files updated
- ✅ **Security** - Tidak ada hardcoded credentials atau vulnerabilities
- ✅ **Performance** - Database queries optimized, no N+1 problems
- ✅ **Backward Compatibility** - Tidak breaking existing functionality

### Merge Strategy

```bash
# Merge dengan merge commit (default)
git merge feature/user-authentication

# Merge dengan squash (1 commit)
git merge --squash feature/user-authentication
git commit -m "feat(auth): add user authentication system"

# Merge dengan rebase (linear history)
git rebase feature/user-authentication
```

### Delete Branch setelah Merge

```bash
# Delete local branch
git branch -d feature/user-authentication

# Delete remote branch
git push origin --delete feature/user-authentication

# Or force delete local branch (if not merged)
git branch -D feature/user-authentication
```

---

## Common Commands

### Viewing History

```bash
# View commit log
git log

# View last 5 commits
git log -5

# View one-liner commits
git log --oneline

# View commits in a branch
git log develop..feature/user-authentication

# View commits yang affect specific file
git log -- app/Controllers/Auth.php

# View commits dengan diff
git log -p

# View commits dalam graphical format
git log --graph --oneline --all
```

### Comparing Changes

```bash
# View changes in working directory
git diff

# View staged changes
git diff --staged

# View changes dalam specific file
git diff app/Controllers/Auth.php

# View differences between branches
git diff develop..feature/user-authentication

# View specific commit
git show abc1234
```

### Undoing Changes

```bash
# Discard changes di working directory
git checkout -- app/Controllers/Auth.php

# Unstage file
git reset HEAD app/Controllers/Auth.php

# Undo last commit (keep changes)
git reset --soft HEAD~1

# Undo last commit (discard changes)
git reset --hard HEAD~1

# Revert commit (create new commit yang undo changes)
git revert abc1234

# Clean up untracked files
git clean -fd
```

### Stashing Changes

```bash
# Save changes tanpa commit
git stash

# View stashed changes
git stash list

# Apply stashed changes
git stash apply

# Apply dan delete stash
git stash pop

# Delete specific stash
git stash drop stash@{0}
```

### Searching

```bash
# Search commit messages
git log --grep="authentication"

# Search dalam code changes
git log -S "password_verify"

# Find commit that introduced a line
git blame app/Models/UserModel.php

# Find when bug was introduced (binary search)
git bisect start
git bisect bad  # current version
git bisect good v1.0  # known good version
```

---

## Troubleshooting

### 1. Merge Conflict

**Masalah:** Git tidak bisa auto-merge dua perubahan

**Solusi:**
```bash
# View conflicted files
git status

# Edit files dan resolve conflicts (cari <<<<<<<, =======, >>>>>>>)
# Edit app/Controllers/Dokumen.php (dan files lainnya)

# Mark as resolved
git add app/Controllers/Dokumen.php

# Complete merge
git commit -m "merge: resolve conflicts in dokumen controller"
```

### 2. Accidental Commit ke Wrong Branch

**Masalah:** Commit ke `main` instead of `develop`

**Solusi:**
```bash
# Undo commit di main
git reset --soft HEAD~1

# Switch to correct branch
git checkout develop

# Commit di correct branch
git commit -m "feat(user): add new feature"

# Push
git push
```

### 3. Need to Undo Pushed Commits

**Masalah:** Push commit yang salah ke remote

**Solusi (untuk non-main branches):**
```bash
# Reset local branch
git reset --hard abc1234  # commit hash sebelum wrong commit

# Force push (HANYA jika tidak ada orang yang sudah pull!)
git push --force-with-lease origin feature/branch-name
```

**Untuk main/develop branches:**
```bash
# Gunakan git revert (lebih safe)
git revert abc1234  # commit hash yang ingin di-undo
git push origin main
```

### 4. Lost Commits atau Accidentally Deleted Branch

**Masalah:** Branch dihapus atau commits tidak terlihat

**Solusi:**
```bash
# Find lost commits
git reflog

# Recover branch
git checkout -b recovered-branch abc1234  # commit hash dari reflog
```

### 5. Large File Accidentally Committed

**Masalah:** File besar (binary, database dump) ter-commit

**Solusi:**
```bash
# Remove file dari last commit
git reset HEAD~1

# Remove file dari history (if already pushed)
git filter-branch --tree-filter 'rm -f large-file.zip' HEAD

# Add ke .gitignore
echo "large-file.zip" >> .gitignore
git add .gitignore
git commit -m "chore: add large-file.zip to gitignore"
```

### 6. Authentication Failed (HTTPS)

**Masalah:** `fatal: Authentication failed for 'https://github.com/...`

**Solusi:**
```bash
# Option 1: Use Personal Access Token
# 1. Generate token di GitHub Settings
# 2. When prompted, use token as password
git push  # Will prompt for credentials

# Option 2: Switch to SSH
git remote set-url origin git@github.com:nanangpx0-hub/monika.git
# Make sure you have SSH key setup

# Option 3: Cache credentials
git config --global credential.helper store
git push  # Will prompt once, then cached
```

### 7. Detached HEAD State

**Masalah:** `HEAD detached at abc1234`

**Solusi:**
```bash
# Go back to branch
git checkout main

# Or create new branch from detached state
git checkout -b new-branch
```

### 8. Accidentally Modified .env or composer.lock

**Masalah:** File sensitive ter-modified dan ingin discard

**Solusi:**
```bash
# Restore specific file dari last commit
git checkout HEAD -- .env

# Or if completely messed up
git reset --hard HEAD
```

---

## Best Practices

### ✅ DO's

- ✅ **Commit often** - Commit setiap perubahan logis
- ✅ **Write descriptive messages** - Message yang jelas help future developers
- ✅ **Pull before push** - Always update local branch sebelum push
- ✅ **Create feature branches** - Gunakan feature branches untuk setiap task
- ✅ **Use .gitignore** - Jangan commit file sensitive
- ✅ **Review code** - Gunakan PR untuk code review
- ✅ **Test before commit** - Pastikan code works sebelum commit
- ✅ **Keep history clean** - Gunakan rebase untuk linear history

### ❌ DON'Ts

- ❌ **Don't commit .env files** - Dieksklusi oleh .gitignore untuk keamanan
- ❌ **Don't commit vendor directory** - Gunakan composer install
- ❌ **Don't use generic messages** - Hindari "fix bug", "update"
- ❌ **Don't force push to main/develop** - Use only untuk feature branches
- ❌ **Don't mix unrelated changes** - 1 PR = 1 feature/fix
- ❌ **Don't commit large files** - Gunakan LFS untuk file >50MB
- ❌ **Don't ignore merge conflicts** - Resolve properly
- ❌ **Don't work directly on main** - Always use branches

---

## References

- [GitHub Documentation](https://docs.github.com/)
- [Conventional Commits](https://www.conventionalcommits.org/)
- [Git Documentation](https://git-scm.com/doc)
- [Git Cheat Sheet](https://github.github.com/training-kit/downloads/github-git-cheat-sheet.pdf)

---

## Support

Jika ada pertanyaan atau issues dengan Git workflow, buat issue di repository atau contact development lead.

**Last Updated:** January 30, 2026
