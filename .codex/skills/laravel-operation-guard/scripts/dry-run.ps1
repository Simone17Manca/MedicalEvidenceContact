param(
    [string]$ProjectRoot = (Get-Location).Path,
    [string]$TestFilter = "",
    [string]$RouteName = "",
    [switch]$SkipMigratePretend
)

$ErrorActionPreference = "Stop"

function Invoke-Step {
    param(
        [string]$Name,
        [scriptblock]$Command
    )

    Write-Host ""
    Write-Host "== $Name =="
    & $Command
}

Set-Location $ProjectRoot

$php = Join-Path $ProjectRoot "tools\php-8.4\php.exe"
$phpIni = Join-Path $ProjectRoot "php-local.ini"
$artisan = Join-Path $ProjectRoot "artisan-local.cmd"

if (-not (Test-Path $php)) {
    throw "PHP runtime not found: $php"
}

if (-not (Test-Path $artisan)) {
    throw "Artisan wrapper not found: $artisan"
}

$changedPhpFiles = git status --short |
    ForEach-Object { $_.Substring(3).Trim() } |
    Where-Object { $_ -like "*.php" -and (Test-Path $_) }

if ($changedPhpFiles.Count -gt 0) {
    Invoke-Step "PHP lint changed files" {
        foreach ($file in $changedPhpFiles) {
            & $php -c $phpIni -l $file
            if ($LASTEXITCODE -ne 0) {
                exit $LASTEXITCODE
            }
        }
    }
} else {
    Write-Host "No changed PHP files found for lint."
}

if ($RouteName -ne "") {
    Invoke-Step "Laravel route dry-run" {
        cmd /c $artisan route:list --name=$RouteName
        if ($LASTEXITCODE -ne 0) {
            exit $LASTEXITCODE
        }
    }
}

if (-not $SkipMigratePretend) {
    Invoke-Step "Laravel migration pretend" {
        cmd /c $artisan migrate --pretend --no-interaction
        if ($LASTEXITCODE -ne 0) {
            exit $LASTEXITCODE
        }
    }
}

if ($TestFilter -ne "") {
    Invoke-Step "PHPUnit targeted" {
        cmd /c $artisan test --filter=$TestFilter
        if ($LASTEXITCODE -ne 0) {
            exit $LASTEXITCODE
        }
    }
} else {
    Write-Host "No PHPUnit filter supplied. Pass -TestFilter <Name> for targeted tests."
}
