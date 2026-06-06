param(
    [string]$RemoteUrl = "https://github.com/mochammadirfanmufarridramadhan/sistem-absensi-mahasiswa.git",
    [string]$Branch = "main"
)

# Optional: allow the caller to pass the exact path to git.exe
[string]$GitPath = ""

function Resolve-Git {
    if (Get-Command git -ErrorAction SilentlyContinue) {
        return "git"
    }

    if ($GitPath -and (Test-Path $GitPath)) {
        $gitDir = Split-Path -Parent $GitPath
        $env:PATH = "$gitDir;$env:PATH"
        return "git"
    }

    $common = @( 
        'C:\Program Files\Git\cmd\git.exe',
        'C:\Program Files (x86)\Git\cmd\git.exe'
    )
    foreach ($p in $common) {
        if (Test-Path $p) {
            $env:PATH = "$(Split-Path -Parent $p);$env:PATH"
            return "git"
        }
    }

    return $null
}

Set-Location $PSScriptRoot

$gitCmd = Resolve-Git
if (-not $gitCmd) {
    Write-Error "Git not found in PATH. Try: restart PowerShell, ensure Git is installed, or pass -GitPath 'C:\\Program Files\\Git\\cmd\\git.exe' to this script. Download: https://git-scm.com/download/win"
    exit 1
}

if (-not (Test-Path .git)) {
    git init
}

git add .

try {
    git commit -m "Initial commit from assistant" -q
} catch {
    Write-Host "Nothing to commit or commit failed: $_"
}

$remotes = git remote
if (-not $remotes) {
    git remote add origin $RemoteUrl
} else {
    Write-Host "Remote already configured: $remotes"
}

git branch -M $Branch

git push -u origin $Branch
