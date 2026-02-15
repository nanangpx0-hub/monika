# MONIKA - Quick Commands Reference

Cheat sheet untuk command yang sering digunakan di MONIKA.

---

## 📝 Documentation Commands

### Update Dokumentasi (Cara Tercepat)
```cmd
update-docs.bat "Pesan perubahan Anda"
```

### Update Dokumentasi (PowerShell)
```powershell
# Update semua
.\scripts\update-docs.ps1 -Type all -Message "Pesan perubahan"

# Update changelog saja
.\scripts\update-docs.ps1 -Type changelog -Message "Pesan perubahan"

# Update struktur project
.\scripts\update-docs.ps1 -Type structure

# Update daftar fitur
.\scripts\update-docs.ps1 -Type features
```

### Generate API Documentation
```powershell
.\scripts\generate-api-docs.ps1
```

### Check Migration Status
```powershell
.\scripts\check-migrations.ps1
```

---

## 🗄️ Database Commands

### Migration
```bash
# Run all pending migrations
php spark migrate

# Check migration status
php spark migrate:status

# Rollback last batch
php spark migrate:rollback

# Rollback all and re-run
php spark migrate:refresh

# Create new migration
php spark make:migration CreateTableName
```

### Seeder
```bash
# Run all seeders
php spark db:seed

# Run specific seeder
php spark db:seed UserSeeder

# Create new seeder
php spark make:seeder UserSeeder
```

---

## 🏗️ Code Generation

### Controller
```bash
php spark make:controller ControllerName
```

### Model
```bash
php spark make:model ModelName
```

### Filter
```bash
php spark make:filter FilterName
```

---

## 🧪 Testing & Debugging

### Clear Cache
```bash
php spark cache:clear
```

### Run Development Server
```bash
php spark serve
```

### Check Routes
```bash
php spark routes
```

---

## 📦 Composer Commands

### Install Dependencies
```bash
composer install
```

### Update Dependencies
```bash
composer update
```

### Dump Autoload
```bash
composer dump-autoload
```

---

## 🔧 Git Commands

### Daily Workflow
```bash
# Check status
git status

# Add all changes
git add .

# Commit with message
git commit -m "feat: Your feature description"

# Push to remote
git push origin main

# Pull latest changes
git pull origin main
```

### Branch Management
```bash
# Create new branch
git checkout -b feature/new-feature

# Switch branch
git checkout main

# Merge branch
git merge feature/new-feature

# Delete branch
git branch -d feature/new-feature
```

---

## 🚀 Complete Workflow

### After Adding New Feature
```powershell
# 1. Update documentation
.\scripts\update-docs.ps1 -Type all -Message "Implementasi modul Logistik"

# 2. Generate API docs
.\scripts\generate-api-docs.ps1

# 3. Check status
git status

# 4. Add and commit
git add .
git commit -m "feat: Implementasi modul Logistik lengkap"

# 5. Push
git push origin main
```

### After Database Changes
```bash
# 1. Run migration
php spark migrate

# 2. Check status
.\scripts\check-migrations.ps1

# 3. Update docs
.\scripts\update-docs.ps1 -Type all -Message "Menambahkan tabel logistik"

# 4. Commit
git add .
git commit -m "feat: Menambahkan tabel logistik"
git push
```

---

## 🔍 Troubleshooting Commands

### PowerShell Execution Policy
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### Check PHP Version
```bash
php -v
```

### Check Composer Version
```bash
composer -V
```

### Check Git Version
```bash
git --version
```

### Test Database Connection
```bash
php spark db:table users
```

---

## 📱 Quick Access

### Open in Browser
```
http://localhost/monika/
```

### Open in VS Code
```bash
code .
```

### Open Documentation
```bash
code docs/
```

---

## 💡 Tips

1. **Gunakan alias untuk command yang sering dipakai:**
   ```powershell
   # Di PowerShell profile
   function docs { .\scripts\update-docs.ps1 -Type all -Message $args }
   
   # Usage
   docs "Pesan perubahan"
   ```

2. **Kombinasi command:**
   ```bash
   php spark migrate && .\scripts\check-migrations.ps1
   ```

3. **Quick commit:**
   ```bash
   git add . && git commit -m "feat: Your message" && git push
   ```

---

**Last Updated:** 2026-02-15
