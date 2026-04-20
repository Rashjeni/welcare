$ErrorActionPreference = "Stop"
$ProgressPreference = "SilentlyContinue"
[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
$exePath = "C:\xampp\htdocs\welcare-desktop"
if (Test-Path $exePath) { Remove-Item -Recurse -Force $exePath }
New-Item -ItemType Directory -Force -Path $exePath

Write-Host "Downloading PHP Desktop (This may take a moment)..."
Invoke-WebRequest -Uri "https://github.com/cztomczak/phpdesktop/releases/download/chrome-v130.1/phpdesktop-chrome-130.1-php-8.3.zip" -OutFile "$exePath\phpdesktop.zip"

Write-Host "Extracting..."
Expand-Archive -Path "$exePath\phpdesktop.zip" -DestinationPath "$exePath\extracted" -Force
Remove-Item "$exePath\phpdesktop.zip"

# Find inner directory
$innerDir = Get-ChildItem "$exePath\extracted" | Select-Object -First 1
Move-Item -Path "$($innerDir.FullName)\*" -Destination $exePath -Force
Remove-Item -Recurse -Force "$exePath\extracted"

Write-Host "Copying Laravel Project..."
if (Test-Path "$exePath\www") { Remove-Item -Recurse -Force "$exePath\www" }
New-Item -ItemType Directory -Force -Path "$exePath\www"

# Only copy necessary files to avoid infinite recursion or heavy copy
Get-ChildItem -Path "C:\xampp\htdocs\welcare" -Exclude "welcare-desktop" | ForEach-Object {
    Copy-Item -Path $_.FullName -Destination "$exePath\www" -Recurse -Force
}

Write-Host "Configuring SQLite Database..."
$envPath = "$exePath\www\.env"
if (Test-Path $envPath) {
    (Get-Content $envPath) -replace 'DB_CONNECTION=mysql', 'DB_CONNECTION=sqlite' `
                           -replace 'DB_HOST=.*', '' `
                           -replace 'DB_PORT=.*', '' `
                           -replace 'DB_DATABASE=.*', '' `
                           -replace 'DB_USERNAME=.*', '' `
                           -replace 'DB_PASSWORD=.*', '' | Set-Content $envPath
}

# Create blank SQLite file
New-Item -ItemType File -Force -Path "$exePath\www\database\database.sqlite"

Write-Host "Updating settings.json..."
$settingsPath = "$exePath\settings.json"
(Get-Content $settingsPath -Raw) -replace '"www_directory":\s*"www"', '"www_directory": "www/public"' | Set-Content $settingsPath

Write-Host "Build complete! Running migrations!"
Set-Location "$exePath\www"
php artisan migrate --force

Write-Host "Done! Execution complete."
