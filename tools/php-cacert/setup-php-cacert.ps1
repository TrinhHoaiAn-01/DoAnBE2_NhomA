param(
    [string] $PhpBin
)

$ErrorActionPreference = 'Stop'

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$caSource = Join-Path $scriptDir 'cacert.pem'

if (-not (Test-Path -LiteralPath $caSource)) {
    Write-Host '[ERROR] Cannot find cacert.pem next to this script.'
    Write-Host "Put cacert.pem in: $scriptDir"
    exit 1
}

if ([string]::IsNullOrWhiteSpace($PhpBin)) {
    $phpCommand = Get-Command php -ErrorAction SilentlyContinue | Select-Object -First 1

    if ($null -ne $phpCommand) {
        $PhpBin = $phpCommand.Source
    }
}

if ([string]::IsNullOrWhiteSpace($PhpBin) -or -not (Test-Path -LiteralPath $PhpBin)) {
    Write-Host '[ERROR] PHP was not found.'
    Write-Host 'Usage:'
    Write-Host '  setup-php-cacert.bat'
    Write-Host '  setup-php-cacert.bat "C:\wamp64\bin\php\php8.3.14\php.exe"'
    exit 1
}

$phpIni = & $PhpBin -r 'echo php_ini_loaded_file();'
$phpIni = $phpIni.Trim()

if ([string]::IsNullOrWhiteSpace($phpIni)) {
    Write-Host '[ERROR] This PHP installation is not loading a php.ini file.'
    Write-Host 'Open php.ini manually or copy php.ini-development to php.ini first.'
    exit 1
}

if (-not (Test-Path -LiteralPath $phpIni)) {
    Write-Host '[ERROR] Loaded php.ini does not exist:'
    Write-Host "  $phpIni"
    exit 1
}

$phpDir = Split-Path -Parent $PhpBin
$caTarget = Join-Path $phpDir 'extras\ssl\cacert.pem'
$caTargetDir = Split-Path -Parent $caTarget

Write-Host 'PHP:'
Write-Host "  $PhpBin"
Write-Host 'php.ini:'
Write-Host "  $phpIni"
Write-Host 'CA target:'
Write-Host "  $caTarget"
Write-Host ''

New-Item -ItemType Directory -Force -Path $caTargetDir | Out-Null
Copy-Item -LiteralPath $caSource -Destination $caTarget -Force

$curlLine = 'curl.cainfo = "' + $caTarget + '"'
$opensslLine = 'openssl.cafile="' + $caTarget + '"'

$lines = [System.Collections.Generic.List[string]]::new()
[string[]] $existingLines = Get-Content -LiteralPath $phpIni
$lines.AddRange($existingLines)

$foundCurl = $false
$foundOpenSsl = $false

for ($i = 0; $i -lt $lines.Count; $i++) {
    if (-not $foundCurl -and $lines[$i] -match '^\s*;?\s*curl\.cainfo\s*=') {
        $lines[$i] = $curlLine
        $foundCurl = $true
        continue
    }

    if (-not $foundOpenSsl -and $lines[$i] -match '^\s*;?\s*openssl\.cafile\s*=') {
        $lines[$i] = $opensslLine
        $foundOpenSsl = $true
        continue
    }
}

if (-not $foundCurl) {
    $lines.Add($curlLine)
}

if (-not $foundOpenSsl) {
    $lines.Add($opensslLine)
}

[System.IO.File]::WriteAllLines($phpIni, $lines, [System.Text.Encoding]::Default)

Write-Host 'Updated php.ini. Current PHP values:'
& $PhpBin -r "echo 'curl.cainfo=' . ini_get('curl.cainfo') . PHP_EOL; echo 'openssl.cafile=' . ini_get('openssl.cafile') . PHP_EOL; echo file_exists(ini_get('curl.cainfo')) ? 'CA file found' . PHP_EOL : 'CA file missing' . PHP_EOL;"

Write-Host ''
Write-Host 'Done. Restart Apache, WAMP, Laragon, XAMPP, or php artisan serve so PHP reloads php.ini.'
