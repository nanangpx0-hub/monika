@echo off
PowerShell -ExecutionPolicy Bypass -NoProfile -File "E:\laragon\www\monika\scripts\git-automation\pre-push.ps1" %*
exit /b %ERRORLEVEL%
