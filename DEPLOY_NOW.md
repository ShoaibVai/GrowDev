# GrowDev Vercel Deployment

GrowDev is now configured for **full-stack Vercel deployment** — both the Laravel backend and Vite frontend run in a single Vercel project using the `@vercel/php` runtime.

## Architecture

```
Request → Vercel CDN
  ├── /build/*, /favicon.ico, /robots.txt, /logo.svg → served as static assets
  └── /* → api/index.php (PHP serverless function)
              → Laravel HTTP kernel
              → Response (HTML, JSON, etc.)
```

- **Frontend**: Built by Vite to `public/build/`, served as static assets
- **Backend**: Laravel 12 running via `@vercel/php` serverless runtime
- **Storage**: Writable paths mapped to `/tmp` (Vercel's ephemeral storage)
- **Session**: `cookie` driver (no server-side file storage)
- **Queue**: `sync` driver (jobs run inline)
- **Cache**: `database` driver (uses Supabase PostgreSQL)
- **Logging**: `stderr` (appears in Vercel function logs)

## Prerequisites

- Node.js 18+
- PHP 8.2+ with Composer (for local build testing)
- [Vercel CLI](https://vercel.com/docs/cli) installed (`npm install -g vercel`)
- Vercel account
- Supabase PostgreSQL database (already configured)

## Setup Environment Variables in Vercel

Set these in your Vercel project dashboard (or via `vercel env add`):

### Application
| Variable | Value |
|---|---|
| `APP_ENV` | `production` |
| `APP_KEY` | Run `php artisan key:generate` locally, paste the value |
| `APP_URL` | `https://growdev.vercel.app` (your Vercel domain) |
| `APP_STORAGE_PATH` | `/tmp/storage` |

### Database (Supabase PostgreSQL)
| Variable | Value |
|---|---|
| `DB_CONNECTION` | `pgsql` |
| `DB_HOST` | Your Supabase DB host |
| `DB_PORT` | `6543` |
| `DB_DATABASE` | `postgres` |
| `DB_USERNAME` | `postgres` |
| `DB_PASSWORD` | Your Supabase DB password |
| `DB_SSL_MODE` | `require` |

### Drivers
| Variable | Value |
|---|---|
| `SESSION_DRIVER` | `cookie` |
| `QUEUE_CONNECTION` | `sync` |
| `CACHE_STORE` | `database` |
| `LOG_CHANNEL` | `stderr` |

### AI (OpenRouter)
| Variable | Value |
|---|---|
| `OPENROUTER_API_KEY` | Your OpenRouter API key |
| `OPENROUTER_MODEL` | `tencent/hy3-preview:free` |

### File Storage (S3 — optional, for future use)
| Variable | Value |
|---|---|
| `FILESYSTEM_DISK` | `s3` |
| `AWS_ACCESS_KEY_ID` | Your AWS key |
| `AWS_SECRET_ACCESS_KEY` | Your AWS secret |
| `AWS_DEFAULT_REGION` | `us-east-1` |
| `AWS_BUCKET` | `growdev-uploads` |

## Deploy

### Option 1: Git Push (Automatic)

Push to the `main` branch. Vercel will automatically:
1. Run `composer install --no-dev --optimize-autoloader`
2. Run `npm install`
3. Run `npm run build` (Vite)
4. Deploy with PHP runtime for `api/index.php`

```bash
git add .
git commit -m "Deploy: update"
git push origin main
```

### Option 2: Vercel CLI

```bash
# Install dependencies
composer install --no-dev --optimize-autoloader
npm install

# Build frontend
npm run build

# Deploy
vercel --prod
```

### Option 3: Deployment Scripts

```bash
# Windows
scripts\deploy-vercel.bat

# Mac/Linux
./scripts/deploy-vercel.sh
```

## Post-Deploy Tasks

### 1. Run Database Migrations

Vercel has no persistent CLI, so run migrations via:

```bash
# Locally (pointing at production DB)
php artisan migrate --force

# Or via GitHub Actions (recommended)
# Add a workflow step:
# - run: php artisan migrate --force
```

### 2. Verify Deployment

- Visit your Vercel URL
- Check the browser console for API connectivity
- Test login, task creation, AI generation
- View logs: `vercel logs`

### 3. Monitor

```bash
vercel logs          # View function logs
vercel logs --error  # View error logs only
```

## Troubleshooting

| Symptom | Likely Cause | Fix |
|---|---|---|
| Blank page / 503 | PHP runtime not installed | Check `@vercel/php` is in `devDependencies` |
| `vendor/autoload.php` not found | Composer not run | Ensure `composer install` runs during build |
| Storage errors | `/tmp` not writable | Verify `APP_STORAGE_PATH=/tmp/storage` is set |
| Database connection refused | Wrong DB env vars | Check `DB_HOST`, `DB_PORT`, credentials |
| 500 Internal Server Error | App key missing | Set `APP_KEY` from `php artisan key:generate` |
| Session issues | Wrong session driver | Set `SESSION_DRIVER=cookie` |
| Queue jobs not running | Wrong queue driver | Set `QUEUE_CONNECTION=sync` |

## Files Changed for Vercel

| File | Purpose |
|---|---|
| `api/index.php` | Serverless function entry point (Laravel bootstrap) |
| `vercel.json` | PHP runtime config, routes, build command |
| `.vercelignore` | Excludes only what's unnecessary at runtime |
| `package.json` | Added `@vercel/php` |
| `composer.json` | Added `league/flysystem-aws-s3-v3` |
| `config/queue.php` | Default queue driver changed to `sync` |
