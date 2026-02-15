# ============================================================================
# MONIKA Git Automation - Pre-Commit Hook
# Runs code quality checks before commit is allowed
# ============================================================================

# Import utilities
$utilsPath = Join-Path (Split-Path -Parent $MyInvocation.MyCommand.Path) "utils.ps1"
. $utilsPath

Write-LogInfo "======= PRE-COMMIT HOOK: Running pre-commit checks ======="

try {
    # Verify we're in a Git repo
    if (-not (Test-GitRepo)) {
        Write-LogError "Not in a Git repository"
        exit 1
    }
    
    # Get staged files (only those ready to be committed)
    try {
        $stagedFiles = @(git diff --cached --name-only 2>$null)
    } catch {
        Write-LogWarning "Could not get staged files"
        $stagedFiles = @()
    }
    
    if ($stagedFiles.Count -eq 0) {
        Write-LogInfo "No staged files to check"
        exit 0
    }
    
    Write-LogInfo "Found $($stagedFiles.Count) staged file(s) to check"
    
    # ============================================================================
    # STEP 1: FILTER EXCLUDED FILES
    # ============================================================================
    
    $filteredFiles = Get-FilteredFiles -Files $stagedFiles
    Write-LogDebug "After filtering: $($filteredFiles.Count) file(s)"
    
    if ($filteredFiles.Count -eq 0) {
        Write-LogInfo "All staged files are excluded from checks"
        exit 0
    }
    
    # ============================================================================
    # STEP 2: RUN CODE QUALITY CHECKS
    # ============================================================================
    
    $qualityPassed = Test-CodeQuality -Files $filteredFiles
    
    if (-not $qualityPassed) {
        Write-LogError "Code quality checks failed"
        Send-Notification -Title "Git Pre-Commit Failed" `
                         -Message "Code quality issues detected" `
                         -Type "ERROR"
        exit 1
    }
    
    # ============================================================================
    # STEP 3: RUN UNIT TESTS
    # ============================================================================
    
    $testsPassed = Test-UnitTests
    
    if (-not $testsPassed) {
        Write-LogError "Unit tests failed"
        Send-Notification -Title "Git Pre-Commit Failed" `
                         -Message "Unit tests failed" `
                         -Type "ERROR"
        exit 1
    }
    
    # ============================================================================
    # STEP 4: CHECK FOR SECURITY ISSUES
    # ============================================================================
    
    Write-LogInfo "Checking for security issues..."
    
    $phpFiles = $filteredFiles | Where-Object { $_ -match "\.php$" }
    
    if ($phpFiles.Count -gt 0) {
        $securityIssues = @()
        
        foreach ($file in $phpFiles) {
            $fullPath = Join-Path $PROJECT_ROOT $file
            
            if (Test-Path $fullPath) {
                try {
                    $content = Get-Content -Path $fullPath -Raw -Encoding UTF8
                    
                    # Check for common security issues
                    if ($content -imatch "(eval|exec|system|shell_exec|passthru|popen|proc_open)\s*\(.*\$_") {
                        Write-LogWarning "Potential security issue in $file`: dynamic code execution"
                        $securityIssues += $file
                    }
                    
                    if ($content -imatch "TODO|FIXME|HACK|XXX" -and $content -imatch "security|password|secret|key") {
                        Write-LogDebug "Found TODO/FIXME related to security in $file"
                    }
                    
                } catch {
                    Write-LogWarning "Could not analyze $file for security issues: $_"
                }
            }
        }
        
        if ($securityIssues.Count -gt 0) {
            Write-LogWarning "Found $($securityIssues.Count) potential security issue(s)"
            Write-LogWarning "Files with issues: $($securityIssues -join ', ')"
            # Warning only, don't block commit
        }
    }
    
    # ============================================================================
    # STEP 5: CHECK FOR LARGE FILES
    # ============================================================================
    
    Write-LogInfo "Checking for large files..."
    
    $MAX_FILE_SIZE = 10 * 1024 * 1024  # 10 MB
    $largeFiles = @()
    
    foreach ($file in $filteredFiles) {
        $fullPath = Join-Path $PROJECT_ROOT $file
        
        if (Test-Path $fullPath -PathType Leaf) {
            $size = (Get-Item -Path $fullPath).Length
            
            if ($size -gt $MAX_FILE_SIZE) {
                Write-LogWarning "Large file detected: $file ($([math]::Round($size/1024/1024, 2)) MB)"
                $largeFiles += $file
            }
        }
    }
    
    if ($largeFiles.Count -gt 0) {
        Write-LogWarning "Found $($largeFiles.Count) large file(s)"
        # Warning only, don't block commit
    }
    
    # ============================================================================
    # SUCCESS
    # ============================================================================
    
    Write-LogSuccess "All pre-commit checks passed!"
    Send-Notification -Title "Git Pre-Commit Passed" `
                     -Message "All checks passed, ready to commit" `
                     -Type "SUCCESS"
    
    exit 0
    
} catch {
    Write-LogError "Unexpected error in pre-commit hook: $_"
    Send-Notification -Title "Git Pre-Commit Error" `
                     -Message "Unexpected error in pre-commit hook" `
                     -Type "ERROR"
    exit 1
}
