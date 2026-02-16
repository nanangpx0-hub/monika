@echo off
REM Deployment script untuk perbaikan login

echo ========================================
echo MONIKA Login Fix Deployment
echo ========================================
echo.
echo PERINGATAN: Script ini akan mengubah database dan konfigurasi sistem.
echo Pastikan Anda sudah backup database sebelum melanjutkan!
echo.
set /p confirm="Lanjutkan deployment? (Y/N): "
if /i not "%confirm%"=="Y" (
    echo Deployment dibatalkan.
    exit /b 0
)
echo.

echo [1/7] Checking environment...
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: PHP tidak ditemukan. Pastikan PHP sudah terinstall dan ada di PATH.
    exit /b 1
)
echo ✓ PHP found
echo.

echo [2/7] Checking database connection...
php spark db:table users >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Database connection failed. Periksa konfigurasi di .env
    echo.
    echo Jalankan diagnostic untuk detail:
    echo   php spark diagnose:login
    exit /b 1
)
echo ✓ Database connection OK
echo.

echo [3/7] Backing up database...
set backup_file=backup_before_login_fix_%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%%time:~6,2%.sql
set backup_file=%backup_file: =0%
echo Creating backup: %backup_file%
mysqldump -u root -pMonika@2026! monika > %backup_file% 2>nul
if %errorlevel% neq 0 (
    echo WARNING: Backup failed. Lanjutkan tanpa backup? (Y/N)
    set /p continue="Continue? "
    if /i not "%continue%"=="Y" exit /b 1
) else (
    echo ✓ Backup created: %backup_file%
)
echo.

echo [4/7] Running migrations...
php spark migrate
if %errorlevel% neq 0 (
    echo ERROR: Migration failed
    echo.
    echo Rollback? (Y/N)
    set /p rollback="Rollback? "
    if /i "%rollback%"=="Y" (
        echo Restoring database...
        mysql -u root -pMonika@2026! monika < %backup_file%
        echo Database restored.
    )
    exit /b 1
)
echo ✓ Migrations completed
echo.

echo [5/7] Creating session directory...
if not exist "writable\session" (
    mkdir writable\session
    echo ✓ Session directory created
) else (
    echo ✓ Session directory already exists
)
echo.

echo [6/7] Running diagnostic...
php spark diagnose:login
echo.

echo [7/7] Running tests...
set /p run_tests="Run tests? (Y/N): "
if /i "%run_tests%"=="Y" (
    echo.
    echo Running unit tests...
    vendor\bin\phpunit tests\unit\AuthTest.php
    if %errorlevel% neq 0 (
        echo WARNING: Some unit tests failed
    ) else (
        echo ✓ Unit tests passed
    )
    echo.
    
    echo Running integration tests...
    vendor\bin\phpunit tests\integration\LoginFlowTest.php
    if %errorlevel% neq 0 (
        echo WARNING: Some integration tests failed
    ) else (
        echo ✓ Integration tests passed
    )
)
echo.

echo ========================================
echo Deployment Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Test login manually di browser
echo 2. Monitor logs: writable\logs\
echo 3. Check login attempts: SELECT * FROM login_attempts;
echo 4. Baca dokumentasi: README_PERBAIKAN_LOGIN.md
echo.
echo Backup location: %backup_file%
echo.
pause
