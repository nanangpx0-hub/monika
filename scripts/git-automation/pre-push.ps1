# ============================================================================
# MONIKA Git Automation - Pre-Push Hook
# Handles automatic push with retry mechanism and error recovery
# ============================================================================

param(
    [string]$RemoteName = "origin",
    [string]$RefsBeforeSha = "0000000000000000000000000000000000000000"
)

# Import utilities
$utilsPath = Join-Path (Split-Path -Parent $MyInvocation.MyCommand.Path) "utils.ps1"
. $utilsPath

Write-LogInfo "======= PRE-PUSH HOOK: Preparing push operation ======="

try {
    # Verify we're in a Git repo
    if (-not (Test-GitRepo)) {
        Write-LogError "Not in a Git repository"
        exit 1
    }
    
    # Get current branch
    $currentBranch = git rev-parse --abbrev-ref HEAD 2>$null
    Write-LogInfo "Current branch: $currentBranch"
    
    # Create checkpoint before push
    $checkpoint = New-Checkpoint
    if (-not $checkpoint) {
        Write-LogError "Failed to create checkpoint"
        exit 1
    }
    
    # ============================================================================
    # STEP 1: FINAL VALIDATION CHECKS
    # ============================================================================
    
    Write-LogInfo "Running final validation checks..."
    
    # Check if we have commits to push
    $commitsToPush = @(git log "$RemoteName/$currentBranch..$currentBranch" --oneline 2>$null)
    Write-LogInfo "Commits to push: $($commitsToPush.Count)"
    
    if ($commitsToPush.Count -eq 0 -and $RefsBeforeSha -ne "0000000000000000000000000000000000000000") {
        Write-LogInfo "No new commits to push"
        exit 0
    }
    
    # ============================================================================
    # STEP 2: CHECK FOR COMMON ISSUES
    # ============================================================================
    
    Write-LogInfo "Checking for common push issues..."
    
    # Check if branch exists on remote
    $remoteExists = git ls-remote --heads $RemoteName $currentBranch 2>$null
    
    if (-not $remoteExists -and $SET_UPSTREAM_ON_PUSH) {
        Write-LogInfo "Branch doesn't exist on remote. Will set upstream during push."
    }
    
    # ============================================================================
    # STEP 3: PERFORM PUSH WITH RETRY
    # ============================================================================
    
    Write-LogInfo "Pushing commits to $RemoteName/$currentBranch..."
    
    $pushCmd = {
        $upstreamFlag = if ($SET_UPSTREAM_ON_PUSH) { "-u" } else { "" }
        
        if ($upstreamFlag) {
            $output = git push -u $RemoteName $currentBranch 2>&1
        } else {
            $output = git push $RemoteName $currentBranch 2>&1
        }
        
        if ($LASTEXITCODE -ne 0) {
            throw "Push failed with exit code $LASTEXITCODE: $output"
        }
        
        Write-LogDebug "Push output: $output"
        return $output
    }
    
    try {
        $result = Invoke-RetryableCommand `
            -Command $pushCmd `
            -Description "Git Push" `
            -MaxAttempts $MAX_RETRY_ATTEMPTS `
            -DelaySeconds $RETRY_DELAY_SECONDS
        
        Write-LogSuccess "Push completed successfully"
        $pushSuccess = $true
    }
    catch {
        Write-LogError "Push failed after $MAX_RETRY_ATTEMPTS attempts: $_"
        $pushSuccess = $false
    }
    
    # ============================================================================
    # STEP 4: POST-PUSH ACTIONS
    # ============================================================================
    
    if ($pushSuccess) {
        Write-LogInfo "Executing post-push tasks..."
        
        # Log the successful push
        $logEntry = @{
            timestamp = Get-Date -Format "o"
            action = "push"
            branch = $currentBranch
            remote = $RemoteName
            commits = $commitsToPush.Count
            status = "success"
        } | ConvertTo-Json
        
        Add-Content -Path (Join-Path $LOGS_DIR "push_history.json") -Value $logEntry
        
        # Send success notification
        Send-Notification -Title "Git Push Successful" `
                         -Message "Pushed $($commitsToPush.Count) commit(s) to $RemoteName/$currentBranch" `
                         -Type "SUCCESS"
        
        Write-LogSuccess "All push operations completed"
        exit 0
    }
    else {
        Write-LogWarning "Push failed, initiating rollback..."
        
        # ============================================================================
        # STEP 5: ROLLBACK ON FAILURE
        # ============================================================================
        
        $rollbackSuccess = Invoke-Rollback -Checkpoint $checkpoint
        
        # Log the failed push
        $logEntry = @{
            timestamp = Get-Date -Format "o"
            action = "push"
            branch = $currentBranch
            remote = $RemoteName
            commits = $commitsToPush.Count
            status = "failed"
            checkpoint = $checkpoint
            rollback_success = $rollbackSuccess
        } | ConvertTo-Json
        
        Add-Content -Path (Join-Path $LOGS_DIR "push_history.json") -Value $logEntry
        
        # Send error notification
        Send-Notification -Title "Git Push Failed" `
                         -Message "Push failed and rolled back to checkpoint: $($checkpoint.Substring(0, 7))" `
                         -Type "ERROR"
        
        Write-LogError "Push operation failed and was rolled back"
        exit 1
    }
    
} catch {
    Write-LogError "Unexpected error in pre-push hook: $_`n$($_.ScriptStackTrace)"
    Send-Notification -Title "Git Push Error" `
                     -Message "Unexpected error during push" `
                     -Type "ERROR"
    exit 1
}
