# ============================================
# MONIKA Migration Status Checker
# ============================================
# Script ini mengecek status migration database
# ============================================

$ErrorActionPreference = "Stop"
$ProjectRoot = Split-Path -Parent $PSScriptRoot
$MigrationsPath = Join-Path $ProjectRoot "app\Database\Migrations"

Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  MONIKA Migration Status Checker" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# ============================================
# Function: Get Migration Files
# ============================================
function Get-MigrationFiles {
    Write-Host "Scanning migration files..." -ForegroundColor Yellow
    
    $migrations = Get-ChildItem $MigrationsPath -Filter "*.php" | 
        Where-Object { $_.Name -ne ".gitkeep" } |
        Sort-Object Name
    
    $migrationList = @()
    
    foreach ($migration in $migrations) {
        # Parse migration name
        if ($migration.Name -match '^(\d{4}-\d{2}-\d{2}-\d{6})_(.+)\.php$') {
            $timestamp = $Matches[1]
            $name = $Matches[2]
            
            $migrationList += [PSCustomObject]@{
                File = $migration.Name
                Timestamp = $timestamp
                Name = $name
                Path = $migration.FullName
            }
        }
    }
    
    return $migrationList
}

# ============================================
# Function: Check Migration Status
# ============================================
function Check-MigrationStatus {
    Write-Host "Checking migration status..." -ForegroundColor Yellow
    Write-Host ""
    
    # Run php spark migrate:status
    Push-Location $ProjectRoot
    
    try {
        $output = & php spark migrate:status 2>&1
        
        Write-Host "Migration Status:" -ForegroundColor Cyan
        Write-Host "----------------------------------------" -ForegroundColor Gray
        Write-Host $output
        Write-Host "----------------------------------------" -ForegroundColor Gray
        Write-Host ""
        
    } catch {
        Write-Host "Warning: Could not run 'php spark migrate:status'" -ForegroundColor Yellow
        Write-Host "Make sure PHP is in PATH and database is configured." -ForegroundColor Yellow
        Write-Host ""
    }
    
    Pop-Location
}

# ============================================
# Function: Display Migration List
# ============================================
function Display-MigrationList {
    param($Migrations)
    
    Write-Host "Available Migrations:" -ForegroundColor Cyan
    Write-Host ""
    
    $counter = 1
    foreach ($migration in $Migrations) {
        Write-Host "  $counter. " -NoNewline -ForegroundColor Gray
        Write-Host "$($migration.Name)" -ForegroundColor White
        Write-Host "     Timestamp: $($migration.Timestamp)" -ForegroundColor DarkGray
        Write-Host "     File: $($migration.File)" -ForegroundColor DarkGray
        Write-Host ""
        $counter++
    }
    
    Write-Host "Total migrations: $($Migrations.Count)" -ForegroundColor Green
    Write-Host ""
}

# ============================================
# Function: Show Migration Commands
# ============================================
function Show-MigrationCommands {
    Write-Host "Useful Migration Commands:" -ForegroundColor Cyan
    Write-Host ""
    
    $commands = @(
        @{
            Command = "php spark migrate"
            Description = "Run all pending migrations"
        },
        @{
            Command = "php spark migrate:status"
            Description = "Check migration status"
        },
        @{
            Command = "php spark migrate:rollback"
            Description = "Rollback last migration batch"
        },
        @{
            Command = "php spark migrate:refresh"
            Description = "Rollback all migrations and re-run"
        },
        @{
            Command = "php spark make:migration CreateTableName"
            Description = "Create new migration file"
        }
    )
    
    foreach ($cmd in $commands) {
        Write-Host "  • " -NoNewline -ForegroundColor Yellow
        Write-Host "$($cmd.Command)" -ForegroundColor White
        Write-Host "    $($cmd.Description)" -ForegroundColor Gray
        Write-Host ""
    }
}

# ============================================
# Function: Analyze Migrations
# ============================================
function Analyze-Migrations {
    param($Migrations)
    
    Write-Host "Migration Analysis:" -ForegroundColor Cyan
    Write-Host ""
    
    # Group by date
    $byDate = $Migrations | Group-Object { $_.Timestamp.Substring(0, 10) }
    
    Write-Host "  Migrations by date:" -ForegroundColor Yellow
    foreach ($group in $byDate | Sort-Object Name -Descending) {
        Write-Host "    $($group.Name): $($group.Count) migration(s)" -ForegroundColor White
    }
    Write-Host ""
    
    # Check for naming patterns
    $tables = @()
    foreach ($migration in $Migrations) {
        if ($migration.Name -match 'Create(\w+)Table') {
            $tables += $Matches[1]
        }
    }
    
    if ($tables.Count -gt 0) {
        Write-Host "  Tables created:" -ForegroundColor Yellow
        foreach ($table in $tables) {
            Write-Host "    • $table" -ForegroundColor White
        }
        Write-Host ""
    }
}

# ============================================
# Main Execution
# ============================================

try {
    # Get migration files
    $migrations = Get-MigrationFiles
    
    if ($migrations.Count -eq 0) {
        Write-Host "No migration files found!" -ForegroundColor Red
        exit 1
    }
    
    # Display migration list
    Display-MigrationList -Migrations $migrations
    
    # Analyze migrations
    Analyze-Migrations -Migrations $migrations
    
    # Check migration status from database
    Check-MigrationStatus
    
    # Show useful commands
    Show-MigrationCommands
    
    Write-Host "================================================" -ForegroundColor Green
    Write-Host "  Done!" -ForegroundColor Green
    Write-Host "================================================" -ForegroundColor Green
    
} catch {
    Write-Host ""
    Write-Host "Error: $_" -ForegroundColor Red
    Write-Host $_.ScriptStackTrace -ForegroundColor Red
    exit 1
}
