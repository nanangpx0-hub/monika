<#
.SYNOPSIS
    Script untuk sinkronisasi otomatis dokumentasi proyek Monika ke GitHub.

.DESCRIPTION
    Script ini memantau perubahan pada file Markdown (.md) dan melakukan commit & push otomatis
    ke repository GitHub. Mendukung mode validasi koneksi, sinkronisasi manual, dan monitoring terus-menerus.

.PARAMETER CheckOnly
    Hanya memvalidasi koneksi ke git remote tanpa melakukan sinkronisasi.

.PARAMETER Sync
    Melakukan satu kali proses add, commit, dan push untuk file dokumentasi yang berubah.

.PARAMETER Watch
    Berjalan dalam loop terus-menerus memantau perubahan file setiap interval waktu tertentu.

.EXAMPLE
    .\sync_docs.ps1 -CheckOnly
    .\sync_docs.ps1 -Sync
    .\sync_docs.ps1 -Watch
#>

param (
    [switch]$CheckOnly,
    [switch]$Sync,
    [switch]$Watch
)

$TargetBranch = "main"
$RemoteName = "origin"
$CommitPrefix = "docs: auto-sync update"
$FilePattern = "*.md"
$WatchIntervalSeconds = 30

function Log-Message {
    param ([string]$Message, [string]$Level = "INFO")
    $Timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    Write-Host "[$Timestamp] [$Level] $Message" -ForegroundColor (Get-Color $Level)
}

function Get-Color {
    param ([string]$Level)
    switch ($Level) {
        "INFO" { return "Cyan" }
        "SUCCESS" { return "Green" }
        "WARNING" { return "Yellow" }
        "ERROR" { return "Red" }
        Default { return "White" }
    }
}

function Check-Git-Environment {
    Log-Message "Memeriksa lingkungan Git..."
    
    if (-not (Get-Command git -ErrorAction SilentlyContinue)) {
        Log-Message "Git tidak ditemukan! Pastikan Git sudah terinstal." "ERROR"
        return $false
    }

    $RepoRoot = git rev-parse --show-toplevel 2>$null
    if (-not $RepoRoot) {
        Log-Message "Direktori saat ini bukan git repository." "ERROR"
        return $false
    }

    $RemoteUrl = git remote get-url $RemoteName 2>$null
    if (-not $RemoteUrl) {
        Log-Message "Remote '$RemoteName' tidak ditemukan." "ERROR"
        return $false
    }

    Log-Message "Lingkungan OK. Remote: $RemoteUrl" "SUCCESS"
    return $true
}

function Sync-Documentation {
    Log-Message "Mengecek perubahan pada file dokumentasi ($FilePattern)..."

    # Cek status hanya untuk file .md
    $Status = git status --porcelain | Select-String "\.md$"

    if (-not $Status) {
        Log-Message "Tidak ada perubahan pada file dokumentasi." "INFO"
        return
    }

    Log-Message "Perubahan terdeteksi:" "WARNING"
    $Status | ForEach-Object { Write-Host $_ }

    Log-Message "Menyiapkan staging area..."
    # Add semua file .md yang berubah (termasuk di subfolder docs/)
    git add *.md docs/*.md 2>$null

    $DateStr = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $CommitMsg = "$CommitPrefix [$DateStr]"

    Log-Message "Melakukan Commit: '$CommitMsg'"
    git commit -m "$CommitMsg" 2>$null
    if ($LASTEXITCODE -ne 0) {
        Log-Message "Gagal melakukan commit." "ERROR"
        return
    }

    Log-Message "Melakukan Pushing ke $RemoteName/$TargetBranch..."
    git push $RemoteName $TargetBranch 2>&1 | Out-String -Stream | ForEach-Object {
        if ($_ -match "error" -or $_ -match "fatal") {
            Log-Message $_ "ERROR"
        } else {
            Write-Host $_
        }
    }

    if ($LASTEXITCODE -eq 0) {
        Log-Message "Sinkronisasi berhasil!" "SUCCESS"
    } else {
        Log-Message "Push gagal. Cek koneksi atau konflik." "ERROR"
    }
}

# --- Main Execution ---

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   MONIKA DOCS SYNC TOOL" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

if (-not (Check-Git-Environment)) {
    exit 1
}

if ($CheckOnly) {
    Log-Message "Validasi koneksi selesai." "SUCCESS"
    exit 0
}

if ($Sync) {
    Sync-Documentation
    exit 0
}

if ($Watch) {
    Log-Message "Memulai Mode Watch (Interval: ${WatchIntervalSeconds}s)..." "INFO"
    Log-Message "Tekan Ctrl+C untuk berhenti." "WARNING"
    
    while ($true) {
        Sync-Documentation
        Start-Sleep -Seconds $WatchIntervalSeconds
    }
}

Log-Message "Gunakan parameter -CheckOnly, -Sync, atau -Watch." "WARNING"
Log-Message "Contoh: .\sync_docs.ps1 -Sync" "INFO"
