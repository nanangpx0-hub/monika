# ============================================================================
# MONIKA Git Automation Configuration
# Centralized configuration for all Git automation scripts
# ============================================================================

# ---- PATHS ----
$SCRIPT_DIR = Split-Path -Parent $MyInvocation.MyCommand.Path
$PROJECT_ROOT = Split-Path -Parent (Split-Path -Parent $SCRIPT_DIR)
$LOGS_DIR = Join-Path $PROJECT_ROOT "writable/logs/git-automation"
$TEMP_DIR = Join-Path $PROJECT_ROOT "writable/git-temp"

# Create necessary directories if they don't exist
if (-not (Test-Path $LOGS_DIR)) { New-Item -ItemType Directory -Path $LOGS_DIR -Force | Out-Null }
if (-not (Test-Path $TEMP_DIR)) { New-Item -ItemType Directory -Path $TEMP_DIR -Force | Out-Null }

# ---- LOGGING CONFIGURATION ----
$LOG_FILE = Join-Path $LOGS_DIR "git-automation-$(Get-Date -Format 'yyyy-MM-dd').log"
$LOG_LEVEL = "INFO" # DEBUG, INFO, WARNING, ERROR

# ---- GIT CONFIGURATION ----
$GIT_USER_NAME = "nanangpx0-hub"
$GIT_USER_EMAIL = "nanangpx0@gmail.com"
$DEFAULT_BRANCH = "main"
$REMOTE_NAME = "origin"

# ---- COMMIT MESSAGE FORMAT ----
# Valid prefixes: feat, fix, chore, refactor, docs, style, test, perf, ci, build
$COMMIT_PREFIXES = @("feat", "fix", "chore", "refactor", "docs", "style", "test", "perf", "ci", "build")

# ---- RETRY CONFIGURATION ----
$MAX_RETRY_ATTEMPTS = 3
$RETRY_DELAY_SECONDS = 5  # Wait time between retries
$RETRY_BACKOFF_MULTIPLIER = 1.5  # Exponential backoff

# ---- NOTIFICATIONS ----
$ENABLE_NOTIFICATIONS = $true
$NOTIFY_SUCCESS = $true
$NOTIFY_ERROR = $true

# ---- CODE QUALITY CHECKS ----
$ENABLE_CODE_QUALITY = $true
$ENABLE_UNIT_TESTS = $false  # Set to $true when tests are ready
$CODE_QUALITY_TOOLS = @(
    # Add PHP CodeSniffer, PHPLint, etc. as available
)

# ---- AUTO-COMMIT CONFIGURATION ----
$AUTO_COMMIT_ENABLED = $true
$AUTO_COMMIT_UNTRACKED_FILES = $false  # Only track modified files by default
$EXCLUDED_PATHS = @(
    "vendor/*",
    "writable/*",
    ".env",
    "composer.lock",
    "*.log"
)

# ---- PUSH CONFIGURATION ----
$SET_UPSTREAM_ON_PUSH = $true
$FORCE_PUSH_ALLOWED = $false

# ---- TIMEOUT VALUES (in seconds) ----
$TEST_TIMEOUT = 60
$PUSH_TIMEOUT = 300
$CODE_QUALITY_TIMEOUT = 120

# Export configuration
Export-ModuleMember -Variable * -Function *
