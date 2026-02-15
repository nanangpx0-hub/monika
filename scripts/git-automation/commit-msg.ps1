# ============================================================================
# MONIKA Git Automation - Commit Message Handler
# Validates and attempts to fix commit message format
# ============================================================================

param(
    [Parameter(Mandatory=$true)]
    [string]$CommitMsgFile
)

# Import utilities
$utilsPath = Join-Path (Split-Path -Parent $MyInvocation.MyCommand.Path) "utils.ps1"
. $utilsPath

Write-LogInfo "======= COMMIT-MSG HOOK: Validating commit message ======="

try {
    # Read the commit message
    $commitMsg = Get-Content -Path $CommitMsgFile -Raw -Encoding UTF8
    $commitMsg = $commitMsg.Trim()
    
    # Skip validation for merge commits and other special commits
    if ($commitMsg -match "^Merge " -or $commitMsg -match "^Revert " -or $commitMsg -match "^\(cherry picked from commit") {
        Write-LogInfo "Skipping validation for auto-generated commit message"
        exit 0
    }
    
    Write-LogDebug "Original message: `"$commitMsg`""
    
    # ============================================================================
    # VALIDATION RULES
    # ============================================================================
    
    $lines = $commitMsg -split "`n"
    $firstLine = $lines[0].Trim()
    
    # Rule 1: Check if first line has valid prefix
    $hasValidPrefix = $false
    $detectedPrefix = $null
    
    foreach ($prefix in $COMMIT_PREFIXES) {
        if ($firstLine -imatch "^$prefix(\(.+\))?:\s.+") {
            $hasValidPrefix = $true
            $detectedPrefix = $prefix
            break
        }
    }
    
    if (-not $hasValidPrefix) {
        Write-LogWarning "Commit message doesn't follow format: 'prefix: description'"
        Write-LogInfo "Valid prefixes: $($COMMIT_PREFIXES -join ', ')"
        Write-LogInfo "Example: 'feat: add new dashboard feature'"
        
        # Try to auto-fix: detect common patterns
        if ($firstLine -imatch "^(bugfix|bug|fix\s|fixed|fixes)") {
            $newMsg = $firstLine -replace "^(bugfix|bug|fix\s|fixed|fixes)\s*", "fix: "
            Write-LogInfo "Auto-fixed prefix to 'fix'"
        }
        elseif ($firstLine -imatch "^(feature|add|added|adds)\s") {
            $newMsg = $firstLine -replace "^(feature|add|added|adds)\s*", "feat: "
            Write-LogInfo "Auto-fixed prefix to 'feat'"
        }
        elseif ($firstLine -imatch "^(chore|refactor|docs|style|test|perf|ci|build)\s+") {
            $matched = $firstLine -match "^(\w+)\s+"
            if ($matched) {
                $prefix = $matches[1].ToLower()
                if ($prefix -in $COMMIT_PREFIXES) {
                    $newMsg = $firstLine -replace "^(\w+)\s+", "$prefix`: "
                    Write-LogInfo "Auto-fixed prefix to '$prefix'"
                }
            }
        }
        else {
            # If we can't auto-fix, add 'chore:' as default
            if ($firstLine -notmatch "^(chore|build|ci|docs|feat|fix|perf|refactor|revert|style|test):") {
                $newMsg = "chore: $firstLine"
                Write-LogInfo "Auto-added 'chore:' prefix"
            }
        }
        
        if ($newMsg) {
            $commitMsg = $newMsg
            foreach ($i in 1..($lines.Count - 1)) {
                $commitMsg += "`n$($lines[$i])"
            }
            Write-LogSuccess "Commit message auto-fixed"
        }
    }
    
    # Rule 2: Check first line length (should be < 72 chars)
    $firstLine = $commitMsg.Split("`n")[0]
    if ($firstLine.Length -gt 72) {
        Write-LogWarning "First line is too long ($($firstLine.Length) chars). Recommended: < 72 chars"
        # Don't fail, just warn
    }
    
    # Rule 3: Check if description exists after prefix
    if ($firstLine -imatch "^(\w+)(\(.+\))?:\s*$") {
        Write-LogWarning "Commit message is missing description after prefix"
        Write-LogError "Please provide a meaningful description"
        exit 1
    }
    
    # Rule 4: Blank line after header (if there's a body)
    if ($lines.Count -gt 1 -and $lines[1].Trim() -ne "") {
        Write-LogWarning "Missing blank line between header and body"
        $newMsg = $commitMsg.Split("`n")[0] + "`n`n"
        for ($i = 1; $i -lt $lines.Count; $i++) {
            $newMsg += "`n$($lines[$i])"
        }
        $commitMsg = $newMsg
        Write-LogInfo "Auto-added blank line after header"
    }
    
    # ============================================================================
    # WRITE BACK THE (POSSIBLY) MODIFIED MESSAGE
    # ============================================================================
    
    Set-Content -Path $CommitMsgFile -Value $commitMsg -Encoding UTF8
    
    Write-LogSuccess "Commit message validation passed"
    Write-LogDebug "Final message: `"$commitMsg`""
    exit 0
    
} catch {
    Write-LogError "Error in commit-msg hook: $_"
    exit 1
}
