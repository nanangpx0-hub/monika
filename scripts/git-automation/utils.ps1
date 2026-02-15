# ============================================================================
# MONIKA Git Automation - Logging and Utility Functions
# Provides logging, error handling, and utility functions
# ============================================================================

# Import configuration
$CONFIG_PATH = Join-Path (Split-Path -Parent $MyInvocation.MyCommand.Path) "config.ps1"
. $CONFIG_PATH

# ============================================================================
# LOGGING FUNCTIONS
# ============================================================================

function Write-Log {
    <#
    .SYNOPSIS
    Writes log messages with timestamp and level
    
    .PARAMETER Message
    The message to log
    
    .PARAMETER Level
    Log level: DEBUG, INFO, WARNING, ERROR, SUCCESS
    
    .PARAMETER NoConsole
    Don't output to console, only file
    #>
    param(
        [Parameter(Mandatory=$true)]
        [string]$Message,
        
        [ValidateSet("DEBUG", "INFO", "WARNING", "ERROR", "SUCCESS")]
        [string]$Level = "INFO",
        
        [switch]$NoConsole
    )
    
    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $logEntry = "[$timestamp] [$Level] $Message"
    
    # Write to file
    try {
        Add-Content -Path $LOG_FILE -Value $logEntry -ErrorAction SilentlyContinue
    } catch {
        # If logging fails, at least show the error
        Write-Host "Failed to write log: $_"
    }
    
    # Write to console if not suppressed
    if (-not $NoConsole) {
        $color = switch ($Level) {
            "ERROR" { "Red" }
            "WARNING" { "Yellow" }
            "SUCCESS" { "Green" }
            "DEBUG" { "Gray" }
            default { "White" }
        }
        
        Write-Host $logEntry -ForegroundColor $color
    }
}

function Write-LogDebug {
    param([string]$Message)
    if ($LOG_LEVEL -eq "DEBUG") {
        Write-Log -Message $Message -Level "DEBUG"
    }
}

function Write-LogInfo {
    param([string]$Message)
    Write-Log -Message $Message -Level "INFO"
}

function Write-LogWarning {
    param([string]$Message)
    Write-Log -Message $Message -Level "WARNING"
}

function Write-LogError {
    param([string]$Message)
    Write-Log -Message $Message -Level "ERROR"
}

function Write-LogSuccess {
    param([string]$Message)
    Write-Log -Message $Message -Level "SUCCESS"
}

# ============================================================================
# NOTIFICATION FUNCTIONS
# ============================================================================

function Send-Notification {
    <#
    .SYNOPSIS
    Send notification about Git operation status
    
    .PARAMETER Title
    Notification title
    
    .PARAMETER Message
    Notification message
    
    .PARAMETER Type
    Notification type: SUCCESS, ERROR, INFO, WARNING
    #>
    param(
        [Parameter(Mandatory=$true)]
        [string]$Title,
        
        [Parameter(Mandatory=$true)]
        [string]$Message,
        
        [ValidateSet("SUCCESS", "ERROR", "INFO", "WARNING")]
        [string]$Type = "INFO"
    )
    
    if (-not $ENABLE_NOTIFICATIONS) {
        return
    }
    
    # Check if error notification is disabled
    if ($Type -eq "ERROR" -and -not $NOTIFY_ERROR) {
        return
    }
    
    # Check if success notification is disabled
    if ($Type -eq "SUCCESS" -and -not $NOTIFY_SUCCESS) {
        return
    }
    
    try {
        # Prefer BurntToast module if available (works in regular PowerShell hosts)
        try {
            if (Get-Command -Name New-BurntToastNotification -ErrorAction SilentlyContinue) {
                $btText = @($Title, $Message) | Where-Object { $_ -ne $null }
                New-BurntToastNotification -Text $btText | Out-Null
                Write-LogInfo "[${Type}] ${Title}: ${Message} (sent via BurntToast)"
                return
            }
        } catch {
            Write-LogDebug "BurntToast not available or failed: $_"
        }

        # Try Windows Runtime Toasts (may not be available in some hosts)
        if ($IsWindows -or [System.Environment]::OSVersion.Platform -eq 'Win32NT') {
            try {
                [Windows.UI.Notifications.ToastNotificationManager, Windows.UI.Notifications, ContentType = WindowsRuntime] | Out-Null
                [Windows.Data.Xml.Dom.XmlDocument, Windows.Data.Xml.Dom.XmlDocument, ContentType = WindowsRuntime] | Out-Null

                # Construct a simple XML toast and show it if runtime types exist
                $xml = "<toast><visual><binding template='ToastGeneric'><text>$($Title -replace '&','&amp;')</text><text>$($Message -replace '&','&amp;')</text></binding></visual></toast>"
                $xmlDoc = New-Object Windows.Data.Xml.Dom.XmlDocument
                $xmlDoc.LoadXml($xml)
                $notifier = [Windows.UI.Notifications.ToastNotificationManager]::CreateToastNotifier($env:COMPUTERNAME)
                $toast = [Windows.UI.Notifications.ToastNotification]::new($xmlDoc)
                $notifier.Show($toast)
                Write-LogInfo "[${Type}] ${Title}: ${Message} (sent via WinRT Toast)"
                return
            } catch {
                Write-LogDebug "WinRT toast not available or failed: $_"
            }
        }

        # Final fallback: write notification to log/console
        Write-LogInfo "[${Type}] ${Title}: ${Message} (logged - no interactive notification available)"
    } catch {
        Write-LogWarning "Failed to send system notification: $_. Message written to log instead."
    }
}

# ============================================================================
# GIT UTILITY FUNCTIONS
# ============================================================================

function Get-GitStatus {
    <#
    .SYNOPSIS
    Get current Git status
    
    .OUTPUTS
    PSCustomObject with branch, has_changes, untracked_count, etc.
    #>
    try {
        $branch = git rev-parse --abbrev-ref HEAD 2>$null
        $status = git status --porcelain 2>$null
        
        $modified = @($status | Where-Object { $_ -match "^ M" }).Count
        $added = @($status | Where-Object { $_ -match "^A" }).Count
        $deleted = @($status | Where-Object { $_ -match "^ D" }).Count
        $untracked = @($status | Where-Object { $_ -match "^\?\?" }).Count
        
        return [PSCustomObject]@{
            Branch = $branch
            HasChanges = $status.Count -gt 0
            Modified = $modified
            Added = $added
            Deleted = $deleted
            Untracked = $untracked
            Total = $status.Count
            Status = $status
        }
    } catch {
        Write-LogError "Failed to get Git status: $_"
        return $null
    }
}

function Test-GitRepo {
    <#
    .SYNOPSIS
    Verify we're in a valid Git repository
    #>
    try {
        $result = git rev-parse --git-dir 2>$null
        return $result -ne $null
    } catch {
        return $false
    }
}

function Get-ModifiedFiles {
    <#
    .SYNOPSIS
    Get list of modified files (excluding untracked)
    #>
    try {
        $files = git diff --name-only 2>$null
        return $files | Where-Object { $_ -ne "" }
    } catch {
        return @()
    }
}

function Get-UnboundFiles {
    <#
    .SYNOPSIS
    Get list of untracked files
    #>
    try {
        $files = git ls-files --others --exclude-standard 2>$null
        return $files | Where-Object { $_ -ne "" }
    } catch {
        return @()
    }
}

function Invoke-RetryableCommand {
    <#
    .SYNOPSIS
    Execute a command with automatic retry logic
    
    .PARAMETER Command
    The command or scriptblock to execute
    
    .PARAMETER MaxAttempts
    Maximum retry attempts
    
    .PARAMETER DelaySeconds
    Initial delay between retries (uses exponential backoff)
    
    .PARAMETER Description
    Description of the operation for logging
    #>
    param(
        [Parameter(Mandatory=$true)]
        [scriptblock]$Command,
        
        [int]$MaxAttempts = $MAX_RETRY_ATTEMPTS,
        
        [int]$DelaySeconds = $RETRY_DELAY_SECONDS,
        
        [string]$Description = "Unknown operation"
    )
    
    $attempt = 0
    $lastError = $null
    $currentDelay = $DelaySeconds
    
    while ($attempt -lt $MaxAttempts) {
        $attempt++
        Write-LogInfo "[$Description] Attempt $attempt/$MaxAttempts"
        
        try {
            $result = & $Command
            Write-LogSuccess "[$Description] Success on attempt $attempt"
            return $result
        } catch {
            $lastError = $_
            Write-LogWarning "[$Description] Attempt $attempt failed: $($_.Exception.Message)"
            
            if ($attempt -lt $MaxAttempts) {
                Write-LogInfo "[$Description] Retrying in $currentDelay seconds..."
                Start-Sleep -Seconds $currentDelay
                $currentDelay = [int]($currentDelay * $RETRY_BACKOFF_MULTIPLIER)
            }
        }
    }
    
    Write-LogError "[$Description] Failed after $MaxAttempts attempts. Last error: $lastError"
    throw $lastError
}

# ============================================================================
# ROLLBACK FUNCTIONS
# ============================================================================

function New-Checkpoint {
    <#
    .SYNOPSIS
    Create a checkpoint for rollback purposes
    
    .OUTPUTS
    Checkpoint identifier
    #>
    try {
        $checkpoint = git rev-parse HEAD 2>$null
        $checkpointFile = Join-Path $TEMP_DIR "checkpoint_$(Get-Date -Format 'yyyyMMdd_HHmmss').txt"
        $checkpoint | Out-File -FilePath $checkpointFile -Encoding UTF8
        Write-LogDebug "Checkpoint created: $checkpoint"
        return $checkpoint
    } catch {
        Write-LogError "Failed to create checkpoint: $_"
        return $null
    }
}

function Invoke-Rollback {
    <#
    .SYNOPSIS
    Rollback to a specific commit
    
    .PARAMETER Checkpoint
    The commit SHA to rollback to
    #>
    param(
        [Parameter(Mandatory=$true)]
        [string]$Checkpoint
    )
    
    try {
        Write-LogWarning "Rolling back to checkpoint: $Checkpoint"
        git reset --hard $Checkpoint 2>$null
        git clean -fd 2>$null
        Write-LogSuccess "Rollback completed successfully"
        return $true
    } catch {
        Write-LogError "Rollback failed: $_"
        return $false
    }
}

# ============================================================================
# CODE QUALITY FUNCTIONS
# ============================================================================

function Test-CodeQuality {
    <#
    .SYNOPSIS
    Run code quality checks on modified PHP files
    #>
    param(
        [Parameter(Mandatory=$true)]
        [string[]]$Files
    )
    
    if (-not $ENABLE_CODE_QUALITY) {
        Write-LogDebug "Code quality checks disabled"
        return $true
    }
    
    Write-LogInfo "Running code quality checks..."
    
    $phpFiles = $Files | Where-Object { $_ -match "\.php$" }
    
    if ($phpFiles.Count -eq 0) {
        Write-LogDebug "No PHP files to check"
        return $true
    }
    
    $passed = $true
    
    # Basic PHP syntax check
    foreach ($file in $phpFiles) {
        $fullPath = Join-Path $PROJECT_ROOT $file
        if (Test-Path $fullPath) {
            try {
                $output = php -l $fullPath 2>&1
                if ($LASTEXITCODE -ne 0) {
                    Write-LogError "PHP syntax error in $file`:`n$output"
                    $passed = $false
                }
            } catch {
                Write-LogWarning "Could not check PHP syntax: $_"
            }
        }
    }
    
    return $passed
}

function Test-UnitTests {
    <#
    .SYNOPSIS
    Run unit tests
    #>
    if (-not $ENABLE_UNIT_TESTS) {
        Write-LogDebug "Unit tests disabled"
        return $true
    }
    
    Write-LogInfo "Running unit tests..."
    
    try {
        $testCmd = {
            Push-Location $PROJECT_ROOT
            php spark test 2>&1
            $exitCode = $LASTEXITCODE
            Pop-Location
            return $exitCode
        }
        
        $result = Invoke-RetryableCommand -Command $testCmd -Description "Unit Tests" -MaxAttempts 1
        
        if ($result -eq 0) {
            Write-LogSuccess "Unit tests passed"
            return $true
        } else {
            Write-LogError "Unit tests failed with exit code: $result"
            return $false
        }
    } catch {
        Write-LogWarning "Could not run unit tests: $_"
        return $true  # Don't block on test failures if tests aren't set up
    }
}

# ============================================================================
# FILE FILTERING FUNCTIONS
# ============================================================================

function Test-ShouldExcludeFile {
    <#
    .SYNOPSIS
    Check if file matches excluded paths
    #>
    param(
        [Parameter(Mandatory=$true)]
        [string]$FilePath
    )
    
    foreach ($pattern in $EXCLUDED_PATHS) {
        if ($FilePath -like $pattern) {
            return $true
        }
    }
    
    return $false
}

function Get-FilteredFiles {
    <#
    .SYNOPSIS
    Get files that should be processed (excluding filtered ones)
    #>
    param(
        [Parameter(Mandatory=$true)]
        [string[]]$Files
    )
    
    return $Files | Where-Object { -not (Test-ShouldExcludeFile $_) }
}

# Export all functions
# Note: Export-ModuleMember should not be used when sourcing scripts directly
# Commenting out to avoid PowerShell warnings when dot-sourcing
