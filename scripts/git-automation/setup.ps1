# ============================================================================
# MONIKA Git Automation - Setup Script
# Installs git hooks and configures the repository
# ============================================================================

param(
    [switch]$Force = $false,
    [switch]$Uninstall = $false
)

# Set UTF-8 encoding
$OutputEncoding = [System.Text.UTF8Encoding]::new()
[Console]::OutputEncoding = [System.Text.UTF8Encoding]::new()

# Get the project root
$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$projectRoot = Split-Path -Parent (Split-Path -Parent $scriptDir)
$gitHooksDir = Join-Path $projectRoot ".git" "hooks"
$githooksDir = Join-Path $projectRoot ".githooks"

Write-Host "=================================================" -ForegroundColor Cyan
Write-Host "  MONIKA Git Automation - Setup & Installation   " -ForegroundColor Cyan
Write-Host "=================================================" -ForegroundColor Cyan

Write-Host ""
Write-Host "Project Root: $projectRoot"
Write-Host "Git Hooks Dir: $gitHooksDir"
Write-Host ""

# ============================================================================
# FUNCTION: Create hook file
# ============================================================================

function New-GitHook {
    param(
        [string]$HookName,
        [string]$ScriptPath,
        [string]$TargetPath
    )
    
    if (-not (Test-Path $ScriptPath)) {
        Write-Host "  [FAIL] Script not found: $ScriptPath" -ForegroundColor Red
        return $false
    }
    
    # Create batch wrapper for PowerShell hook
    $batchLines = @(
        "@echo off",
        "PowerShell -ExecutionPolicy Bypass -NoProfile -File `"$ScriptPath`" %*",
        "exit /b %ERRORLEVEL%"
    )
    $batchContent = $batchLines -join "`r`n"
    
    try {
        Set-Content -Path $TargetPath -Value $batchContent -Encoding ASCII -Force
        Write-Host "  [OK] Installed: $HookName" -ForegroundColor Green
        return $true
    }
    catch {
        Write-Host "  [FAIL] Failed to install $HookName : $_" -ForegroundColor Red
        return $false
    }
}

# ============================================================================
# MAIN LOGIC
# ============================================================================

if ($Uninstall) {
    Write-Host ""
    Write-Host "Uninstalling Git hooks..." -ForegroundColor Yellow
    
    @("pre-commit", "commit-msg", "pre-push") | ForEach-Object {
        $hookPath = Join-Path $gitHooksDir $_
        if (Test-Path $hookPath) {
            Remove-Item -Force $hookPath
            Write-Host "  [OK] Removed: $_" -ForegroundColor Green
        }
    }
    
    Write-Host ""
    Write-Host "Git hooks uninstalled successfully!" -ForegroundColor Green
    exit 0
}

# Check if Git hooks directory exists
if (-not (Test-Path $gitHooksDir)) {
    Write-Host "Creating .git/hooks directory..." -ForegroundColor Yellow
    New-Item -ItemType Directory -Path $gitHooksDir -Force | Out-Null
}

Write-Host ""
Write-Host "Installing Git Hooks..." -ForegroundColor Yellow

# Install hooks
$hookInstalled = @()
$hookInstalled += (New-GitHook `
    -HookName "pre-commit" `
    -ScriptPath (Join-Path $scriptDir "pre-commit.ps1") `
    -TargetPath (Join-Path $gitHooksDir "pre-commit.cmd")
)

$hookInstalled += (New-GitHook `
    -HookName "commit-msg" `
    -ScriptPath (Join-Path $scriptDir "commit-msg.ps1") `
    -TargetPath (Join-Path $gitHooksDir "commit-msg.cmd")
)

$hookInstalled += (New-GitHook `
    -HookName "pre-push" `
    -ScriptPath (Join-Path $scriptDir "pre-push.ps1") `
    -TargetPath (Join-Path $gitHooksDir "pre-push.cmd")
)

# ============================================================================
# CONFIGURE GIT
# ============================================================================

Write-Host ""
Write-Host "Configuring Git..." -ForegroundColor Yellow

try {
    # Configure git to use custom hooks directory
    git config core.hooksPath ".githooks"
    Write-Host "  [OK] Set core.hooksPath" -ForegroundColor Green
    
    # Configure commit message format
    git config commit.template ".git/commit.template"
    Write-Host "  [OK] Set commit.template" -ForegroundColor Green
}
catch {
    Write-Host "  [WARN] Could not configure git: $_" -ForegroundColor Yellow
}

# ============================================================================
# CREATE COMMIT TEMPLATE
# ============================================================================

$commitTemplate = @"
# <type>(<scope>): <subject>
#
# <body>
#
# Footer (optional)
#
# ============================================================================
# FORMAT GUIDE:
# type: feat, fix, chore, refactor, docs, style, test, perf, ci, build
# scope: optional, area of codebase (e.g., dashboard, auth, models)
# subject: brief description (< 50 chars), imperative mood
#
# EXAMPLE:
# feat(dashboard): add real-time monitoring widget
#
# The dashboard now includes a live widget that updates anomaly counts
# every 5 seconds. This improvement helps supervisors track errors in
# real-time without manual page refresh.
#
# Closes #123
# ============================================================================
"@

try {
    $templatePath = Join-Path $projectRoot ".git" "commit.template"
    Set-Content -Path $templatePath -Value $commitTemplate -Encoding UTF8 -Force
    Write-Host "  [OK] Created commit template" -ForegroundColor Green
}
catch {
    Write-Host "  [WARN] Could not create commit template: $_" -ForegroundColor Yellow
}

# ============================================================================
# SETUP SUMMARY
# ============================================================================

Write-Host ""
Write-Host "=================================================" -ForegroundColor Cyan
Write-Host "  SETUP COMPLETE" -ForegroundColor Cyan
Write-Host "=================================================" -ForegroundColor Cyan

if ($hookInstalled -contains $false) {
    Write-Host ""
    Write-Host "[WARN] Some hooks failed to install!" -ForegroundColor Yellow
    Write-Host "Please check the errors above and try again." -ForegroundColor Yellow
}
else {
    Write-Host ""
    Write-Host "[OK] All git hooks installed successfully!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Your workflows:" -ForegroundColor Cyan
    Write-Host "  1. Make changes to your code"
    Write-Host "  2. Stage commits: git add <files>"
    Write-Host "  3. Commit: git commit"
    Write-Host "     + pre-commit hook runs quality checks"
    Write-Host "     + commit-msg hook validates message format"
    Write-Host "  4. Push: git push"
    Write-Host "     + pre-push hook ensures everything is ready"
    Write-Host "     + Automatic retry if network issues"
    Write-Host "     + Automatic rollback if push fails"
    Write-Host ""
    Write-Host "Configuration:" -ForegroundColor Cyan
    Write-Host "  * Edit: scripts/git-automation/config.ps1"
    Write-Host "  * Logs: writable/logs/git-automation/"
    Write-Host "  * Uninstall: .\scripts\git-automation\setup.ps1 -Uninstall" -ForegroundColor Yellow
}

Write-Host ""
