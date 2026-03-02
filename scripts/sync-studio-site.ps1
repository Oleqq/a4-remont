param(
    [Parameter(Mandatory = $true)]
    [string]$StudioSitePath,

    [string]$ThemeSlug = "a4-remont",

    [switch]$SyncPlugins
)

$ErrorActionPreference = "Stop"

function Assert-Directory {
    param(
        [Parameter(Mandatory = $true)]
        [string]$Path,

        [Parameter(Mandatory = $true)]
        [string]$Label
    )

    if (-not (Test-Path -LiteralPath $Path -PathType Container)) {
        throw "$Label not found: $Path"
    }
}

function Assert-NotReparsePoint {
    param(
        [Parameter(Mandatory = $true)]
        [string]$Path,

        [Parameter(Mandatory = $true)]
        [string]$Label
    )

    if (-not (Test-Path -LiteralPath $Path)) {
        return
    }

    $item = Get-Item -LiteralPath $Path -Force

    if ($item.Attributes -band [IO.FileAttributes]::ReparsePoint) {
        throw "$Label is a junction/symlink and must be removed first: $Path"
    }
}

function Sync-Directory {
    param(
        [Parameter(Mandatory = $true)]
        [string]$SourcePath,

        [Parameter(Mandatory = $true)]
        [string]$DestinationPath
    )

    if (-not (Test-Path -LiteralPath $DestinationPath)) {
        New-Item -ItemType Directory -Path $DestinationPath | Out-Null
    }

    $arguments = @(
        $SourcePath,
        $DestinationPath,
        '/MIR',
        '/FFT',
        '/R:2',
        '/W:2',
        '/NFL',
        '/NDL',
        '/NJH',
        '/NJS',
        '/NP',
        '/XD', '.git', '.github', 'node_modules'
    )

    & robocopy @arguments | Out-Null
    $exitCode = $LASTEXITCODE

    if ($exitCode -gt 7) {
        throw "robocopy failed with exit code $exitCode for $SourcePath"
    }
}

$repoRoot = (Resolve-Path (Join-Path $PSScriptRoot "..")).Path
$studioSiteRoot = (Resolve-Path $StudioSitePath).Path

$studioThemesPath = Join-Path $studioSiteRoot "wp-content\themes"
$studioPluginsPath = Join-Path $studioSiteRoot "wp-content\plugins"
$themeSourcePath = Join-Path $repoRoot "wp-content\themes\$ThemeSlug"
$themeDestinationPath = Join-Path $studioThemesPath $ThemeSlug
$pluginsSourcePath = Join-Path $repoRoot "wp-content\plugins"

Assert-Directory -Path $studioSiteRoot -Label "Studio site path"
Assert-Directory -Path $studioThemesPath -Label "Studio themes directory"
Assert-Directory -Path $studioPluginsPath -Label "Studio plugins directory"
Assert-Directory -Path $themeSourcePath -Label "Theme source directory"
Assert-Directory -Path $pluginsSourcePath -Label "Project plugins directory"

Assert-NotReparsePoint -Path $themeDestinationPath -Label "Theme destination"
Sync-Directory -SourcePath $themeSourcePath -DestinationPath $themeDestinationPath
Write-Host "Theme synced: $themeSourcePath -> $themeDestinationPath" -ForegroundColor Green

if ($SyncPlugins) {
    $pluginDirectories = Get-ChildItem -LiteralPath $pluginsSourcePath -Directory

    foreach ($plugin in $pluginDirectories) {
        $pluginDestinationPath = Join-Path $studioPluginsPath $plugin.Name
        Assert-NotReparsePoint -Path $pluginDestinationPath -Label "Plugin destination"
        Sync-Directory -SourcePath $plugin.FullName -DestinationPath $pluginDestinationPath
        Write-Host "Plugin synced: $($plugin.FullName) -> $pluginDestinationPath" -ForegroundColor Green
    }
}

Write-Host ""
Write-Host "Next steps:" -ForegroundColor Cyan
Write-Host "1. Refresh the WordPress Studio site."
Write-Host "2. Keep editing code in this repository."
Write-Host "3. Re-run this script after file changes, or automate it later with a watcher."
