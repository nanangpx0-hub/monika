@echo off
PowerShell -ExecutionPolicy Bypass -NoProfile -File "E:\laragon\www\monika\scripts\git-automation\commit-msg.ps1" %*
exit /b %ERRORLEVEL%
