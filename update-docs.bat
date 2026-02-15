@echo off
REM ============================================
REM MONIKA - Quick Documentation Update
REM ============================================
REM Shortcut untuk update dokumentasi
REM ============================================

echo.
echo ================================================
echo   MONIKA Documentation Update
echo ================================================
echo.

if "%1"=="" (
    echo Usage:
    echo   update-docs.bat "Your change message"
    echo.
    echo Example:
    echo   update-docs.bat "Menambahkan modul Uji Petik"
    echo.
    pause
    exit /b 1
)

echo Updating documentation...
echo.

powershell -ExecutionPolicy Bypass -File ".\scripts\update-docs.ps1" -Type all -Message "%*"

echo.
echo ================================================
echo   Done!
echo ================================================
echo.
pause
