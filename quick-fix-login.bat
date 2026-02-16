@echo off
REM Quick Fix untuk Login Issue

echo ========================================
echo MONIKA Login Quick Fix
echo ========================================
echo.

echo [1/3] Clearing session files...
del /Q writable\session\ci_session* 2>nul
if %errorlevel% equ 0 (
    echo ✓ Session files cleared
) else (
    echo ✓ No session files to clear
)
echo.

echo [2/3] Clearing cache...
del /Q writable\cache\* 2>nul
echo ✓ Cache cleared
echo.

echo [3/3] Verifying CSRF configuration...
php -r "require 'vendor/autoload.php'; $c = new Config\Security(); echo 'CSRF Regenerate: ' . ($c->regenerate ? 'true (BAD)' : 'false (GOOD)') . PHP_EOL; echo 'CSRF Redirect: ' . ($c->redirect ? 'true (BAD)' : 'false (GOOD)') . PHP_EOL;"
echo.

echo ========================================
echo Fix Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Close ALL browser windows
echo 2. Open NEW Incognito/Private window
echo 3. Go to: http://localhost/monika/login
echo 4. Login with:
echo    Username: admin
echo    Password: Monika@2026!
echo.
echo If still fails, read: FIX_LOGIN_CSRF.md
echo.
pause
