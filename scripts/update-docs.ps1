# ============================================
# MONIKA Documentation Auto-Update Script
# ============================================
# Script ini mengupdate dokumentasi otomatis
# berdasarkan perubahan di codebase
# ============================================

param(
    [string]$Type = "all",  # all, changelog, features, structure
    [string]$Message = ""
)

$ErrorActionPreference = "Stop"
$ProjectRoot = Split-Path -Parent $PSScriptRoot
$DocsDir = Join-Path $ProjectRoot "docs"
$Timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
$Date = Get-Date -Format "yyyy-MM-dd"

Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  MONIKA Documentation Auto-Update" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# ============================================
# Function: Update Changelog
# ============================================
function Update-Changelog {
    param([string]$Message)
    
    Write-Host "[1/4] Updating CHANGELOG..." -ForegroundColor Yellow
    
    $changelogFile = Join-Path $ProjectRoot "CHANGELOG.md"
    
    if (-not (Test-Path $changelogFile)) {
        # Create new changelog
        $content = @"
# Changelog - MONIKA

All notable changes to this project will be documented in this file.

## [$Date] - Latest Changes

### Added
- $Message

"@
        Set-Content -Path $changelogFile -Value $content -Encoding UTF8
        Write-Host "  ✓ CHANGELOG.md created" -ForegroundColor Green
    } else {
        # Update existing changelog
        $content = Get-Content $changelogFile -Raw
        
        if ($content -match "## \[$Date\]") {
            # Add to today's section
            $content = $content -replace "(## \[$Date\].*?### Added)", "`$1`n- $Message"
        } else {
            # Create new section for today
            $newSection = @"

## [$Date] - Latest Changes

### Added
- $Message

"@
            $content = $content -replace "(# Changelog - MONIKA.*?\n\n)", "`$1$newSection"
        }
        
        Set-Content -Path $changelogFile -Value $content -Encoding UTF8
        Write-Host "  ✓ CHANGELOG.md updated" -ForegroundColor Green
    }
}

# ============================================
# Function: Scan Project Structure
# ============================================
function Get-ProjectStructure {
    Write-Host "[2/4] Scanning project structure..." -ForegroundColor Yellow
    
    $structure = @{
        Controllers = @()
        Models = @()
        Views = @()
        Migrations = @()
        Routes = @()
    }
    
    # Scan Controllers
    $controllersPath = Join-Path $ProjectRoot "app\Controllers"
    if (Test-Path $controllersPath) {
        $structure.Controllers = Get-ChildItem $controllersPath -Filter "*.php" | 
            Where-Object { $_.Name -ne "BaseController.php" -and $_.Name -ne "Home.php" } |
            Select-Object -ExpandProperty BaseName
    }
    
    # Scan Models
    $modelsPath = Join-Path $ProjectRoot "app\Models"
    if (Test-Path $modelsPath) {
        $structure.Models = Get-ChildItem $modelsPath -Filter "*.php" | 
            Where-Object { $_.Name -ne ".gitkeep" } |
            Select-Object -ExpandProperty BaseName
    }
    
    # Scan Views
    $viewsPath = Join-Path $ProjectRoot "app\Views"
    if (Test-Path $viewsPath) {
        $structure.Views = Get-ChildItem $viewsPath -Directory | 
            Where-Object { $_.Name -ne "errors" -and $_.Name -ne "layout" } |
            Select-Object -ExpandProperty Name
    }
    
    # Scan Migrations
    $migrationsPath = Join-Path $ProjectRoot "app\Database\Migrations"
    if (Test-Path $migrationsPath) {
        $structure.Migrations = Get-ChildItem $migrationsPath -Filter "*.php" | 
            Where-Object { $_.Name -ne ".gitkeep" } |
            Select-Object -ExpandProperty BaseName
    }
    
    Write-Host "  ✓ Found $($structure.Controllers.Count) controllers" -ForegroundColor Green
    Write-Host "  ✓ Found $($structure.Models.Count) models" -ForegroundColor Green
    Write-Host "  ✓ Found $($structure.Views.Count) view folders" -ForegroundColor Green
    Write-Host "  ✓ Found $($structure.Migrations.Count) migrations" -ForegroundColor Green
    
    return $structure
}

# ============================================
# Function: Update Project Structure Doc
# ============================================
function Update-StructureDoc {
    param($Structure)
    
    Write-Host "[3/4] Updating project structure documentation..." -ForegroundColor Yellow
    
    $structureFile = Join-Path $DocsDir "PROJECT_STRUCTURE.md"
    
    $content = @"
# MONIKA - Project Structure

**Last Updated:** $Timestamp

## Overview
This document provides an overview of the MONIKA project structure and components.

---

## Controllers ($($Structure.Controllers.Count))

"@
    
    foreach ($controller in $Structure.Controllers | Sort-Object) {
        $content += "- ``$controller.php``"
        
        # Try to get description from file
        $controllerFile = Join-Path $ProjectRoot "app\Controllers\$controller.php"
        if (Test-Path $controllerFile) {
            $firstLines = Get-Content $controllerFile -TotalCount 20
            $classLine = $firstLines | Where-Object { $_ -match "class $controller" }
            if ($classLine) {
                $content += " - Controller for $controller module"
            }
        }
        $content += "`n"
    }
    
    $content += @"

---

## Models ($($Structure.Models.Count))

"@
    
    foreach ($model in $Structure.Models | Sort-Object) {
        $content += "- ``$model.php``"
        
        # Try to get table name
        $modelFile = Join-Path $ProjectRoot "app\Models\$model.php"
        if (Test-Path $modelFile) {
            $fileContent = Get-Content $modelFile -Raw
            if ($fileContent -match "protected \`$table\s*=\s*'([^']+)'") {
                $tableName = $Matches[1]
                $content += " - Table: ``$tableName``"
            }
        }
        $content += "`n"
    }
    
    $content += @"

---

## Views ($($Structure.Views.Count))

"@
    
    foreach ($view in $Structure.Views | Sort-Object) {
        $viewPath = Join-Path $ProjectRoot "app\Views\$view"
        $viewFiles = Get-ChildItem $viewPath -Filter "*.php" -ErrorAction SilentlyContinue
        $content += "- ``$view/`` ($($viewFiles.Count) files)`n"
    }
    
    $content += @"

---

## Database Migrations ($($Structure.Migrations.Count))

"@
    
    foreach ($migration in $Structure.Migrations | Sort-Object) {
        $content += "- ``$migration.php```n"
    }
    
    $content += @"

---

## Directory Tree

``````
monika/
├── app/
│   ├── Controllers/     # Application controllers
│   ├── Models/          # Database models
│   ├── Views/           # View templates
│   ├── Config/          # Configuration files
│   ├── Database/
│   │   ├── Migrations/  # Database migrations
│   │   └── Seeds/       # Database seeders
│   ├── Filters/         # Request filters
│   └── Helpers/         # Helper functions
├── public/
│   ├── assets/          # CSS, JS, images
│   └── index.php        # Front controller
├── writable/
│   ├── cache/           # Cache files
│   ├── logs/            # Log files
│   └── uploads/         # Uploaded files
├── docs/                # Documentation
├── scripts/             # Utility scripts
└── vendor/              # Composer dependencies
``````

---

**Generated by:** MONIKA Documentation Auto-Update Script  
**Date:** $Timestamp
"@
    
    Set-Content -Path $structureFile -Value $content -Encoding UTF8
    Write-Host "  ✓ PROJECT_STRUCTURE.md updated" -ForegroundColor Green
}

# ============================================
# Function: Update Features List
# ============================================
function Update-FeaturesDoc {
    param($Structure)
    
    Write-Host "[4/4] Updating features documentation..." -ForegroundColor Yellow
    
    $featuresFile = Join-Path $DocsDir "FEATURES_LIST.md"
    
    $content = @"
# MONIKA - Features List

**Last Updated:** $Timestamp

## Implemented Features

### 1. Authentication & Authorization
- ✅ Login/Logout
- ✅ Session Management
- ✅ Role-based Access Control

### 2. Modules

"@
    
    # Map controllers to features
    $featureMap = @{
        "Dashboard" = "Dashboard with statistics and overview"
        "TandaTerima" = "Tanda Terima - Document receipt management"
        "Presensi" = "Presensi - Attendance tracking"
        "KartuKendali" = "Kartu Kendali Digital - Entry tracking system"
        "UjiPetik" = "Uji Petik Kualitas - Quality control sampling"
        "Dokumen" = "Dokumen - Document management"
        "Kegiatan" = "Kegiatan - Activity/Survey management"
        "Laporan" = "Laporan - Reporting system"
        "Monitoring" = "Monitoring - Performance monitoring"
        "Logistik" = "Logistik - Logistics management"
    }
    
    foreach ($controller in $Structure.Controllers | Sort-Object) {
        if ($featureMap.ContainsKey($controller)) {
            $content += "#### $controller`n"
            $content += "- ✅ $($featureMap[$controller])`n`n"
        }
    }
    
    $content += @"

### 3. Database Tables

"@
    
    foreach ($model in $Structure.Models | Sort-Object) {
        $modelFile = Join-Path $ProjectRoot "app\Models\$model.php"
        if (Test-Path $modelFile) {
            $fileContent = Get-Content $modelFile -Raw
            if ($fileContent -match "protected \`$table\s*=\s*'([^']+)'") {
                $tableName = $Matches[1]
                $content += "- ``$tableName`` (Model: $model)`n"
            }
        }
    }
    
    $content += @"

---

## Feature Status Summary

- **Total Controllers:** $($Structure.Controllers.Count)
- **Total Models:** $($Structure.Models.Count)
- **Total View Modules:** $($Structure.Views.Count)
- **Total Migrations:** $($Structure.Migrations.Count)

---

**Generated by:** MONIKA Documentation Auto-Update Script  
**Date:** $Timestamp
"@
    
    Set-Content -Path $featuresFile -Value $content -Encoding UTF8
    Write-Host "  ✓ FEATURES_LIST.md updated" -ForegroundColor Green
}

# ============================================
# Main Execution
# ============================================

try {
    # Ensure docs directory exists
    if (-not (Test-Path $DocsDir)) {
        New-Item -ItemType Directory -Path $DocsDir -Force | Out-Null
    }
    
    # Get project structure
    $structure = Get-ProjectStructure
    
    # Update based on type
    switch ($Type.ToLower()) {
        "changelog" {
            if ([string]::IsNullOrWhiteSpace($Message)) {
                Write-Host "Error: Message required for changelog update" -ForegroundColor Red
                Write-Host "Usage: .\update-docs.ps1 -Type changelog -Message 'Your change description'" -ForegroundColor Yellow
                exit 1
            }
            Update-Changelog -Message $Message
        }
        "structure" {
            Update-StructureDoc -Structure $structure
        }
        "features" {
            Update-FeaturesDoc -Structure $structure
        }
        "all" {
            if (-not [string]::IsNullOrWhiteSpace($Message)) {
                Update-Changelog -Message $Message
            }
            Update-StructureDoc -Structure $structure
            Update-FeaturesDoc -Structure $structure
        }
        default {
            Write-Host "Error: Invalid type '$Type'" -ForegroundColor Red
            Write-Host "Valid types: all, changelog, structure, features" -ForegroundColor Yellow
            exit 1
        }
    }
    
    Write-Host ""
    Write-Host "================================================" -ForegroundColor Green
    Write-Host "  Documentation updated successfully!" -ForegroundColor Green
    Write-Host "================================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "Updated files:" -ForegroundColor Cyan
    Write-Host "  - CHANGELOG.md" -ForegroundColor White
    Write-Host "  - docs/PROJECT_STRUCTURE.md" -ForegroundColor White
    Write-Host "  - docs/FEATURES_LIST.md" -ForegroundColor White
    Write-Host ""
    
} catch {
    Write-Host ""
    Write-Host "Error: $_" -ForegroundColor Red
    Write-Host $_.ScriptStackTrace -ForegroundColor Red
    exit 1
}
