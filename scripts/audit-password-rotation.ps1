#!/usr/bin/env pwsh
Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

$projectRoot = (Resolve-Path (Join-Path $PSScriptRoot '..')).Path
Set-Location $projectRoot

if (-not (Get-Command rg -ErrorAction SilentlyContinue)) {
    Write-Error "ripgrep (rg) tidak ditemukan. Install rg terlebih dahulu."
    exit 2
}

$targetPassword = 'Monika@2026!'

$unexpectedHits = @()

$hashFiles = @(
    'app/Database/Seeds/AdminSeeder.php',
    'app/Database/Seeds/UserSeeder.php',
    'tests/feature/AuthTest.php'
)

foreach ($file in $hashFiles) {
    $content = Get-Content -Path $file -Raw
    $matches = [regex]::Matches($content, "password_hash\('([^']+)'")
    foreach ($match in $matches) {
        if ($match.Groups[1].Value -ne $targetPassword) {
            $unexpectedHits += "$file => password_hash menggunakan nilai non-standar"
        }
    }
}

$envChecks = @(
    @{ Path = 'env'; Regex = '^database\.default\.password\s*=\s*(.+)$'; Expected = $targetPassword },
    @{ Path = '.env'; Regex = '^database\.default\.password\s*=\s*(.+)$'; Expected = $targetPassword },
    @{ Path = 'env'; Regex = '^#\s*database\.tests\.password\s*=\s*(.+)$'; Expected = $targetPassword },
    @{ Path = '.env'; Regex = '^#\s*database\.tests\.password\s*=\s*(.+)$'; Expected = $targetPassword }
)

foreach ($check in $envChecks) {
    $line = Get-Content -Path $check.Path | Where-Object { $_ -match $check.Regex } | Select-Object -First 1
    if (-not $line) {
        $unexpectedHits += "$($check.Path) => baris konfigurasi tidak ditemukan: $($check.Regex)"
        continue
    }

    $value = ([regex]::Match($line, $check.Regex)).Groups[1].Value.Trim()
    if ($value -ne $check.Expected) {
        $unexpectedHits += "$($check.Path) => nilai tidak sesuai standar"
    }
}

$docChecks = @(
    @{ Path = 'docs/KARTU_KENDALI_QUICKSTART.md'; Regex = 'Password:\s*`([^`]+)`'; Expected = $targetPassword },
    @{ Path = 'fix_schema.sql'; Regex = '--\s*Password:\s*(.+)$'; Expected = $targetPassword }
)

foreach ($check in $docChecks) {
    $content = Get-Content -Path $check.Path -Raw
    $match = [regex]::Match($content, $check.Regex, [System.Text.RegularExpressions.RegexOptions]::Multiline)
    if (-not $match.Success) {
        $unexpectedHits += "$($check.Path) => referensi password tidak ditemukan"
        continue
    }

    $value = $match.Groups[1].Value.Trim()
    if ($value -ne $check.Expected) {
        $unexpectedHits += "$($check.Path) => nilai tidak sesuai standar"
    }
}

if ($unexpectedHits.Count -gt 0) {
    Write-Host '[GAGAL] Ditemukan ketidakkonsistenan password:' -ForegroundColor Red
    $unexpectedHits | ForEach-Object { Write-Host " - $_" }
    exit 1
}

$requiredChecks = @(
    @{ Path = 'app/Database/Seeds/AdminSeeder.php'; Pattern = "password_hash\('Monika@2026!'" },
    @{ Path = 'app/Database/Seeds/UserSeeder.php'; Pattern = "password_hash\('Monika@2026!'" },
    @{ Path = 'app/Database/Seeds/UserDummySeeder.php'; Pattern = "DEFAULT_PASSWORD = 'Monika@2026!'" },
    @{ Path = 'tests/feature/AuthTest.php'; Pattern = "'password' => 'Monika@2026!'" },
    @{ Path = 'env'; Pattern = 'database.default.password = Monika@2026!' },
    @{ Path = '.env'; Pattern = 'database.default.password = Monika@2026!' },
    @{ Path = 'docs/KARTU_KENDALI_QUICKSTART.md'; Pattern = 'Password: `Monika@2026!`' }
)

$missing = @()
foreach ($check in $requiredChecks) {
    $content = Get-Content -Path $check.Path -Raw
    if ($content -notmatch $check.Pattern) {
        $missing += "$($check.Path) => missing pattern: $($check.Pattern)"
    }
}

if ($missing.Count -gt 0) {
    Write-Host '[GAGAL] Password baru belum konsisten di semua file wajib:' -ForegroundColor Red
    $missing | ForEach-Object { Write-Host " - $_" }
    exit 1
}

Write-Host '[OK] Audit password lulus. Tidak ada ketidakkonsistenan yang terdeteksi.' -ForegroundColor Green
Write-Host '[OK] Password Monika@2026! sudah terpasang di semua file wajib.' -ForegroundColor Green
