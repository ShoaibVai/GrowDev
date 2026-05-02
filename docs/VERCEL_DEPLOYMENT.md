# GrowDev Vercel Deployment Guide

This document provides comprehensive instructions for deploying GrowDev with the frontend on Vercel and the backend on a separate platform.

## Architecture Overview

```
┌─────────────────────────────────────────────────────────────┐
│                    Vercel (Frontend)                        │
│  - Vite build output                                        │
│  - Alpine.js, Tailwind CSS                                  │
│  - Static assets + SPA routing                              │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           │ HTTPS API calls
                           │
        ┌──────────────────▼──────────────────────┐
        │   Backend Platform (Heroku/Railway/Fly.io)  │
        │  - Laravel 12 application                   │
        │  - PostgreSQL/MySQL database               │
        │  - Redis queue (optional)                  │
        │  - Background job processing               │
        └───────────────────────────────────────────┘
```

## Part 1: Frontend Deployment on Vercel

### 1.1 Prerequisites
- Vercel account (free tier available at https://vercel.com)
- GitHub account with GrowDev repository
- Node.js 18+ installed locally for testing

### 1.2 Setup Vercel Project

#### Option A: Via GitHub (Recommended)
1. Go to https://vercel.com/new
2. Import your GrowDev repository
3. Vercel will auto-detect Vite configuration
4. Configure environment variables (see section 1.4)
5. Deploy

#### Option B: Via Vercel CLI
```bash
# Install Vercel CLI globally
npm install -g vercel

# Login to Vercel
vercel login

# Deploy from project directory
vercel
```

### 1.3 Build Configuration
Vercel automatically detects and uses:
- **Build Command**: `npm run build`
- **Output Directory**: `public/build`
- **Install Command**: `npm install`

The `vercel.json` file at the project root configures:
- Security headers (X-Content-Type-Options, X-Frame-Options, etc.)
- Cache-Control headers for static assets (long-term caching)
- API proxy rules (if using same domain backend)

### 1.4 Environment Variables

Set these in Vercel project settings (Settings → Environment Variables):

| Variable | Value | Notes |
|----------|-------|-------|
| `VITE_GEMINI_API_KEY` | `your-new-gemini-key` | **CRITICAL**: Use a NEW key (old key is compromised) |
| `VITE_API_URL` | `https://api.yourdomain.com` | Backend API base URL |

**To generate a new Gemini API key:**
1. Go to Google AI Studio: https://aistudio.google.com/
2. Create new API key (old key `AIzaSyCWK2g9CeDcvQThgaHdWKSIwvJRbbV-Ib8` is exposed)
3. Copy new key to Vercel environment variables
4. Update local `.env` file with new key

### 1.5 Testing Frontend Build Locally

Before deploying to Vercel:

```bash
# Install dependencies
npm install

# Create .env.local with API URL
echo "VITE_API_URL=http://localhost:8000" > .env.local
echo "VITE_GEMINI_API_KEY=your-new-key" >> .env.local

# Test production build locally
npm run build

# Verify build output
ls -la public/build/

# Test with local backend (if running)
npm run dev  # Default: http://localhost:5173
```

### 1.6 Security Considerations

- ✅ `vercel.json` includes security headers
- ✅ Vite automatically handles CSP headers
- ✅ `.vercelignore` excludes sensitive PHP/Laravel files
- ✅ Environment variables are encrypted in Vercel
- ✅ No sensitive data in `public/build/` output

### 1.7 Troubleshooting Frontend Deployment

**Build fails with "module not found":**
```bash
rm -rf node_modules package-lock.json
npm install
npm run build
```

**API calls failing (CORS errors):**
- Check `VITE_API_URL` environment variable is set
- Verify backend CORS config includes Vercel domain
- Check browser console for detailed error

**Assets not loading (404 errors):**
- Verify `npm run build` creates files in `public/build/`
- Check Vercel deployment logs
- Ensure no hardcoded paths in CSS/JS

---

## Part 2: Backend Deployment (Choose One Platform)

### 2.1 Shared Requirements

**Database Setup:**
- PostgreSQL 14+ OR MySQL 8.0+
- Create database: `growdev`
- Managed services recommended:
  - **Neon** (PostgreSQL): https://neon.tech/ (free tier, easy setup)
  - **PlanetScale** (MySQL): https://planetscale.com/ (free tier)
  - **AWS RDS**: https://aws.amazon.com/rds/ (more expensive)

**Queue System:**
- Default: SQLite-based job queue (included)
- Optional: Redis for better performance
  - **Redis Cloud**: https://redis.com/cloud/ (free tier)
  - **Heroku Redis**: $15/month addon (if using Heroku)

**Storage:**
- Local file uploads to application storage
- For production: migrate to S3/Cloud Storage later

### 2.2 Deploy to Heroku

**Prerequisites:**
- Heroku account (https://heroku.com)
- Heroku CLI installed
- Credit card on file (for PostgreSQL add-on)

**Setup:**

```bash
# Login to Heroku
heroku login

# Create new Heroku app
heroku create growdev-backend
# Or existing app: heroku apps:create --stack heroku-22

# Add PostgreSQL add-on
heroku addons:create heroku-postgresql:mini --app=growdev-backend

# Set environment variables
heroku config:set APP_ENV=production APP_DEBUG=false --app=growdev-backend
heroku config:set GEMINI_API_KEY=your-new-key --app=growdev-backend
heroku config:set CORS_ALLOWED_ORIGINS=https://yourdomain.vercel.app,https://yourdomain.com --app=growdev-backend

# Deploy from git
git push heroku main

# Run migrations
heroku run php artisan migrate:fresh --seed --app=growdev-backend

# View logs
heroku logs --tail --app=growdev-backend

# Start queue worker (optional but recommended)
heroku ps:scale queue=1 --app=growdev-backend
```

**Procfile:**
```
web: vendor/bin/heroku-php-apache2 public/
queue: php artisan queue:work --sleep=3 --tries=3
scheduler: php artisan schedule:work
```

The `Procfile` in this project is already configured for Heroku.

### 2.3 Deploy to Fly.io

**Prerequisites:**
- Fly.io account (https://fly.io)
- `flyctl` CLI installed: https://fly.io/docs/hands-on/install-flyctl/

**Setup:**

```bash
# Login to Fly.io
flyctl auth login

# Create PostgreSQL database
flyctl postgres create --name growdev-db

# Attach database to app (will be configured in fly.toml)
flyctl postgres attach growdev-db --app growdev-backend

# Set environment variables
flyctl secrets set APP_ENV=production --app growdev-backend
flyctl secrets set APP_DEBUG=false --app growdev-backend
flyctl secrets set GEMINI_API_KEY=your-new-key --app growdev-backend
flyctl secrets set CORS_ALLOWED_ORIGINS=https://yourdomain.vercel.app,https://yourdomain.com --app growdev-backend

# Deploy
flyctl deploy --app growdev-backend

# Run migrations
flyctl ssh console --app growdev-backend
# In console: php artisan migrate:fresh --seed

# View logs
flyctl logs --app growdev-backend
```

The `fly.toml` in this project is configured for Fly.io deployment.

### 2.4 Deploy with Docker

**Build locally:**
```bash
docker build -t growdev:latest .

docker run -p 8000:8000 \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  -e DB_CONNECTION=pgsql \
  -e DB_HOST=your-db-host \
  -e DB_DATABASE=growdev \
  -e DB_USERNAME=user \
  -e DB_PASSWORD=password \
  growdev:latest
```

**Deploy to Docker Hub:**
```bash
# Tag image
docker tag growdev:latest yourusername/growdev:latest

# Push to Docker Hub
docker push yourusername/growdev:latest

# Deploy to your container platform
# (Google Cloud Run, AWS ECS, DigitalOcean, etc.)
```

### 2.5 Environment Variables Reference

| Variable | Required | Default | Notes |
|----------|----------|---------|-------|
| `APP_ENV` | Yes | `production` | Must be `production` |
| `APP_DEBUG` | Yes | `false` | Must be `false` in production |
| `APP_KEY` | Yes | - | Generate with `php artisan key:generate` |
| `DB_CONNECTION` | Yes | `pgsql` | Use `pgsql` for PostgreSQL |
| `DB_HOST` | Yes | - | Database host URL |
| `DB_DATABASE` | Yes | `growdev` | Database name |
| `DB_USERNAME` | Yes | - | Database user |
| `DB_PASSWORD` | Yes | - | Database password |
| `GEMINI_API_KEY` | Yes | - | New Google Gemini API key |
| `CORS_ALLOWED_ORIGINS` | Yes | - | Frontend domain(s), comma-separated |
| `QUEUE_CONNECTION` | No | `database` | Use `redis` if Redis available |
| `CACHE_STORE` | No | `database` | Use `redis` if Redis available |
| `SESSION_DRIVER` | No | `database` | Database-backed sessions |

---

## Part 3: Post-Deployment Configuration

### 3.1 CORS Setup

The backend CORS is configured in `config/cors.php`. To allow your Vercel frontend:

```bash
# Set on your backend platform
CORS_ALLOWED_ORIGINS=https://yourdomain.vercel.app,https://yourdomain.com
```

The configuration supports:
- Specific domains: `https://yourdomain.vercel.app`
- Wildcard patterns: `#^https://.*\.vercel\.app$#`
- Multiple origins: comma-separated list

### 3.2 Database Migration & Seeding

After first deployment, initialize the database:

```bash
# Heroku
heroku run php artisan migrate:fresh --seed --app=growdev-backend

# Fly.io
flyctl ssh console --app growdev-backend
php artisan migrate:fresh --seed

# Docker
docker exec container-name php artisan migrate:fresh --seed
```

This creates:
- All application tables
- System roles
- Demo data (ShowcaseDataSeeder)
- Admin account: `admin@growdev.com` / `password`

### 3.3 Queue Worker

For background jobs (notifications, task processing):

```bash
# Heroku - scale queue process
heroku ps:scale queue=1

# Fly.io - enable queue process
flyctl machines create --from-image growdev --name growdev-queue --process-group queue

# Docker - run in separate container
docker run -d --name growdev-queue growdev php artisan queue:work
```

### 3.4 Scheduled Tasks

For cron jobs (cleanup, reporting):

```bash
# Heroku
heroku ps:scale scheduler=1

# Fly.io
flyctl machines create --from-image growdev --name growdev-scheduler --process-group scheduler

# Docker
docker run -d --name growdev-scheduler growdev php artisan schedule:work
```

---

## Part 4: Testing & Verification

### 4.1 Frontend Verification Checklist

- [ ] Vercel build successful (no errors)
- [ ] Frontend loads at `https://yourdomain.vercel.app`
- [ ] No console errors or security warnings
- [ ] CSS/Tailwind styles applied correctly
- [ ] All fonts and images load
- [ ] Navigation works (no 404s)

### 4.2 Backend Verification Checklist

- [ ] Health check endpoint returns 200: `GET /health`
- [ ] API responds to requests: `GET /api/user` (with auth)
- [ ] Database migrations completed
- [ ] CORS headers present in response
- [ ] Logs accessible for debugging

### 4.3 Integration Tests

```bash
# Test API connectivity from frontend
# In browser console at https://yourdomain.vercel.app:

fetch('https://api.yourdomain.com/api/health')
  .then(r => r.text())
  .then(console.log)

# Should see: "OK"
```

### 4.4 Authentication Flow

1. Open frontend at `https://yourdomain.vercel.app`
2. Register new account or login with `admin@growdev.com`
3. Verify JWT token stored in localStorage
4. Verify API calls include `Authorization: Bearer <token>`
5. Test task creation, status changes, etc.

---

## Part 5: Monitoring & Maintenance

### 5.1 Logging

**Vercel:** Deployment logs in Vercel dashboard

**Backend logs:**
```bash
# Heroku
heroku logs --tail

# Fly.io
flyctl logs --follow

# Docker
docker logs -f container-name
```

### 5.2 Performance Monitoring

- Set up error tracking: Sentry, Rollbar
- Monitor database performance: Platform dashboards
- Monitor queue performance: Check job success rates
- Set up uptime monitoring: UptimeRobot, Pingdom

### 5.3 Database Backups

- Heroku: Auto backup enabled on paid plans
- Fly.io: Enable daily backups in dashboard
- Cloud SQL: Enable automated backups
- Manual backup:
  ```bash
  heroku pg:backups:capture
  flyctl postgres backups create growdev-db
  ```

### 5.4 Security Updates

- Monitor PHP/Laravel security advisories
- Update dependencies regularly:
  ```bash
  composer update
  npm update
  ```
- Rotate API keys periodically
- Review access logs for suspicious activity

---

## Part 6: Troubleshooting

### Frontend Issues

**Vercel build fails:**
- Check `npm run build` works locally
- Verify all dependencies in `package.json`
- Check environment variables set in Vercel
- Review build logs in Vercel dashboard

**Frontend can't reach API:**
- Verify `VITE_API_URL` is set correctly
- Check CORS headers in API response
- Verify backend is running and accessible
- Check browser console for detailed errors

### Backend Issues

**Database connection fails:**
```bash
# Test connection locally
php artisan tinker
DB::connection()->getPdo();  // Should return PDO object
```

**Migrations fail:**
```bash
# Check migration status
php artisan migrate:status

# Rollback and retry
php artisan migrate:rollback
php artisan migrate
```

**Queue not processing:**
```bash
# Check queue status
php artisan queue:failed  # See failed jobs

# Retry failed job
php artisan queue:retry all

# Clear stuck jobs
php artisan queue:flush
```

---

## Part 7: Going Live Checklist

### Before Production Deployment

- [ ] Security audit completed (see AUDIT_FINDINGS.md)
- [ ] All secrets removed from code
- [ ] Gemini API key rotated (old key revoked)
- [ ] Database backup strategy in place
- [ ] Monitoring/alerting configured
- [ ] Error tracking enabled (Sentry, etc.)
- [ ] CORS properly configured for frontend domain
- [ ] SSL certificates valid (Vercel & backend)
- [ ] Rate limiting configured
- [ ] DDoS protection enabled

### First Week Monitoring

- [ ] Monitor error rates daily
- [ ] Check database performance
- [ ] Verify backups are working
- [ ] Review user activity patterns
- [ ] Test disaster recovery procedures

---

## Support & Resources

- **Vercel Docs:** https://vercel.com/docs
- **Fly.io Docs:** https://fly.io/docs/
- **Heroku Docs:** https://devcenter.heroku.com/
- **Laravel Docs:** https://laravel.com/docs/12.x
- **Docker Docs:** https://docs.docker.com/

## Key Contacts

- **Vercel Support:** support@vercel.com
- **Fly.io Support:** support@fly.io
- **Heroku Support:** https://help.heroku.com/

---

**Last Updated:** May 2, 2026
**GrowDev Version:** Latest
**Documentation Status:** Complete
