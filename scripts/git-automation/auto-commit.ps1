# ============================================================================
# MONIKA Git Automation - Auto-Commit Script
# Automatically stages, commits, and pushes changes with smart messages
# ============================================================================

param(
    [string]$CustomMessage = "",
    [switch]$DryRun = $false,
    [switch]$SkipTests = $false,
    [switch]$Force = $false
)

# Import utilities
$utilsPath = Join-Path (Split-Path -Parent $MyInvocation.MyCommand.Path) "utils.ps1"
. $utilsPath

Write-LogInfo "================ AUTO-COMMIT STARTED ================"

try {
    # Verify we're in a Git repo
    if (-not (Test-GitRepo)) {
        Write-LogError "Not in a Git repository"
        exit 1
    }
    
    # ============================================================================
    # STEP 1: GET GIT STATUS
    # ============================================================================
    
    Write-LogInfo "Analyzing repository state..."
    
    $status = Get-GitStatus
    
    if (-not $status.HasChanges) {
        Write-LogInfo "No changes to commit"
        exit 0
    }
    
    Write-LogInfo "Found changes:"
    Write-LogInfo "  Modified: $($status.Modified)"
    Write-LogInfo "  Added: $($status.Added)"
    Write-LogInfo "  Deleted: $($status.Deleted)"
    Write-LogInfo "  Untracked: $($status.Untracked)"
    
    # ============================================================================
    # STEP 2: DETERMINE COMMIT TYPE (AUTO-CLASSIFICATION)
    # ============================================================================
    
    Write-LogInfo "Analyzing changes to determine commit type..."
    
    $modifiedFiles = Get-ModifiedFiles
    $untrackedFiles = Get-UnboundFiles
    
    $allChangedFiles = @()
    $allChangedFiles += $modifiedFiles
    if ($AUTO_COMMIT_UNTRACKED_FILES) {
        $allChangedFiles += $untrackedFiles
    }
    
    # Classify change type
    $commitType = "chore"
    $scope = ""
    $description = ""
    
    # Analyze files to determine type
    foreach ($file in $allChangedFiles) {
        if ($file -match "Models/|Database/") {
            if ($commitType -eq "chore") {
                $commitType = "feat"
                $scope = "database"
            }
        }
        elseif ($file -match "Controllers/") {
            if ($commitType -eq "chore") {
                $commitType = "feat"
                $scope = "controllers"
            }
        }
        elseif ($file -match "Views/") {
            if ($commitType -eq "chore") {
                $commitType = "feat"
                $scope = "views"
            }
        }
        elseif ($file -match "Config/") {
            if ($commitType -eq "chore") {
                $commitType = "chore"
                $scope = "config"
            }
        }
        elseif ($file -match "tests/") {
            if ($commitType -eq "chore") {
                $commitType = "test"
            }
        }
        elseif ($file -match "docs/|README|\.md$") {
            if ($commitType -eq "chore") {
                $commitType = "docs"
            }
        }
    }
    
    # Create description
    if ($status.Modified -gt 0 -and $status.Added -eq 0 -and $status.Deleted -eq 0) {
        $description = "update $($status.Modified) file(s)"
    }
    elseif ($status.Added -gt 0 -and $status.Modified -eq 0) {
        $description = "add $($status.Added) new file(s)"
    }
    else {
        $description = "update multiple files"
    }
    
    # Build commit message
    if ($CustomMessage) {
        $commitMsg = $CustomMessage
    }
    else {
        if ($scope) {
            $commitMsg = "${commitType}(${scope}): ${description}"
        }
        else {
            $commitMsg = "${commitType}: ${description}"
        }
    }
    
    Write-LogInfo "Commit message: $commitMsg"
    Write-LogDebug "Changed files:"
    foreach ($file in $allChangedFiles) {
        Write-LogDebug "  - $file"
    }
    
    # ============================================================================
    # STEP 3: DRY RUN (OPTIONAL)
    # ============================================================================
    
    if ($DryRun) {
        Write-LogInfo "══════ DRY RUN MODE ══════"
        Write-LogInfo "Would stage $($allChangedFiles.Count) file(s)"
        Write-LogInfo "Would commit with message: $commitMsg"
        Write-LogInfo "Would push to: $($status.Branch)"
        Write-LogInfo "Dry run complete. No changes made."
        exit 0
    }
    
    # ============================================================================
    # STEP 4: STAGE FILES
    # ============================================================================
    
    Write-LogInfo "Staging files..."
    
    $filesToStage = Get-FilteredFiles -Files $allChangedFiles
    
    if ($filesToStage.Count -eq 0) {
        Write-LogWarning "No files to stage after filtering"
        exit 0
    }
    
    try {
        foreach ($file in $filesToStage) {
            git add $file 2>$null
        }
        Write-LogSuccess "Staged $($filesToStage.Count) file(s)"
    }
    catch {
        Write-LogError "Failed to stage files: $_"
        exit 1
    }
    
    # ============================================================================
    # STEP 5: RUN PRE-COMMIT CHECKS
    # ============================================================================
    
    if (-not $SkipTests) {
        Write-LogInfo "Running pre-commit checks..."
        
        # Run code quality
        $qualityPassed = Test-CodeQuality -Files $filesToStage
        if (-not $qualityPassed) {
            Write-LogError "Code quality checks failed"
            git reset 2>$null  # Unstage files
            exit 1
        }
        
        # Run tests
        $testsPassed = Test-UnitTests
        if (-not $testsPassed) {
            Write-LogError "Unit tests failed"
            git reset 2>$null  # Unstage files
            exit 1
        }
    }
    else {
        Write-LogWarning "Skipping pre-commit checks (--SkipTests)"
    }
    
    # ============================================================================
    # STEP 6: CREATE CHECKPOINT
    # ============================================================================
    
    $checkpoint = New-Checkpoint
    if (-not $checkpoint) {
        Write-LogError "Failed to create checkpoint"
        exit 1
    }
    
    # ============================================================================
    # STEP 7: COMMIT
    # ============================================================================
    
    Write-LogInfo "Creating commit..."
    
    try {
        $commitOutput = git commit -m $commitMsg 2>&1
        Write-LogDebug "Commit output: $commitOutput"
        Write-LogSuccess "Commit created successfully"
    }
    catch {
        Write-LogError "Failed to create commit: $_"
        git reset 2>$null  # Unstage files
        exit 1
    }
    
    # ============================================================================
    # STEP 8: PUSH
    # ============================================================================
    
    Write-LogInfo "Pushing to remote..."
    
    $pushCmd = {
        $output = git push -u $REMOTE_NAME $status.Branch 2>&1
        if ($LASTEXITCODE -ne 0) {
            throw "Push failed: $output"
        }
        return $output
    }
    
    try {
        $result = Invoke-RetryableCommand `
            -Command $pushCmd `
            -Description "Auto-Push" `
            -MaxAttempts $MAX_RETRY_ATTEMPTS `
            -DelaySeconds $RETRY_DELAY_SECONDS
        
        Write-LogSuccess "Push completed successfully!"
    }
    catch {
        Write-LogError "Push failed: $_"
        
        # Try to rollback
        Write-LogWarning "Attempting to rollback..."
        Invoke-Rollback -Checkpoint $checkpoint
        
        exit 1
    }
    
    # ============================================================================
    # SUCCESS
    # ============================================================================
    
    Write-LogSuccess "=========================================="
    Write-LogSuccess "Auto-Commit Completed Successfully!"
    Write-LogSuccess "=========================================="
    Write-LogSuccess "Commit: $commitMsg"
    Write-LogSuccess "Branch: $($status.Branch)"
    Write-LogSuccess "Files: $($filesToStage.Count)"
    
    Send-Notification -Title "Auto-Commit Successful" -Message "Committed and pushed: ${commitMsg}" -Type "SUCCESS"
    
    exit 0
    
} catch {
    Write-LogError "Unexpected error: $_"
    $errorMsg = $_.Exception.Message
    Send-Notification -Title "Auto-Commit Failed" -Message "Error: $errorMsg" -Type "ERROR"
    exit 1
}
