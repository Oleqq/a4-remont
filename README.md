# A4 Remont

WordPress project repository with two layers:

- `static/` keeps the source static build and reference markup.
- `wp-content/` keeps the deployable theme and custom plugins.

## Structure

- `wp-content/themes/a4-remont` is the active project theme.
- `wp-content/plugins` is reserved for project plugins.

## GitHub Actions

- `Theme CI` validates PHP syntax and builds zip artifacts for the theme and managed custom plugins.
- `Deploy WordPress Artifacts` performs manual SSH/rsync deployment from GitHub Actions.

## Deployment strategy

- The theme is always deployed to `wp-content/themes/a4-remont`.
- Custom plugins are deployed only when `deploy_plugins=true`.
- The workflow does not sync the whole `wp-content` directory, so it does not touch uploads or third-party plugins.

## Required GitHub environment secrets

- `DEPLOY_HOST`
- `DEPLOY_PORT`
- `DEPLOY_USER`
- `DEPLOY_PATH`
- `DEPLOY_SSH_KEY`

## Recommended setup

1. Create `staging` and `production` environments in GitHub.
2. Add the deploy secrets to each environment.
3. Put approval rules on `production`.
4. Keep only project-owned plugins inside `wp-content/plugins` in this repository.

## Local development with WordPress Studio

Recommended workflow for this repository:

1. Create a new local site in WordPress Studio.
2. Keep this Git repository outside the Studio folder and edit code here.
3. Create your local machine config from [.studio.local.example.json](/c:/dev/a4-remont/.studio.local.example.json).
4. Sync the project theme into the Studio site.

One-time sync:

```powershell
npm run studio:sync:local
```

Shared/manual one-time sync is still available:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\sync-studio-site.ps1 -StudioSitePath "C:\Users\<user>\Studio\<your-site-folder>"
```

After that, open the Studio site and activate the `a4-remont` theme in WP Admin.

Note: Studio can break `theme.json` reads when a theme is attached through a symlink or junction. Direct sync is the safer approach.

### Live development mode

Install local tooling once:

```powershell
npm install
```

Recommended local command:

```powershell
npm run studio:live:local
```

What this does:

- performs an initial sync of the theme into the Studio site
- watches theme files in this repository
- copies changed files into the Studio site on save
- reloads the browser automatically through BrowserSync

Important: during live development open the BrowserSync URL printed in the terminal, usually `http://localhost:3000`, not the raw Studio URL.

If you also want to sync project plugins in live mode:

```powershell
npm run studio:live:local:plugins
```

If you need the shared/manual form without local config:

```powershell
npm run studio:live -- --studioSitePath "C:\Users\<user>\Studio\<your-site-folder>" --studioUrl "http://localhost:8881"
```

## Project model

This repository uses a repo-first WordPress theme workflow:

- `static/` is the source static build layer
- `wp-content/` is the deployable WordPress layer
- WordPress Studio is the local runtime
- GitHub Actions provide CI for validation/build and CD for controlled deployment

In practice, this is a hybrid setup:

- local development uses Studio + file sync/live reload
- delivery uses GitHub-based CI/CD
- production deploys only managed WordPress artifacts from the repository
