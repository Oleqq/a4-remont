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
