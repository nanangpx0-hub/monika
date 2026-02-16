@echo off
REM Script untuk menjalankan semua test login

echo ========================================
echo MONIKA Login Testing Suite
echo ========================================
echo.

echo [1/4] Running migrations...
php spark migrate
if %errorlevel% neq 0 (
    echo ERROR: Migration failed
    exit /b 1
)
echo.

echo [2/4] Seeding test data...
php spark db:seed UserSeeder
if %errorlevel% neq 0 (
    echo ERROR: Seeding failed
    exit /b 1
)
echo.

echo [3/4] Running unit tests...
vendor\bin\phpunit tests\unit\AuthTest.php
if %errorlevel% neq 0 (
    echo ERROR: Unit tests failed
    exit /b 1
)
echo.

echo [4/4] Running integration tests...
vendor\bin\phpunit tests\integration\LoginFlowTest.php
if %errorlevel% neq 0 (
    echo ERROR: Integration tests failed
    exit /b 1
)
echo.

echo ========================================
echo All tests passed successfully!
echo ========================================
pause
