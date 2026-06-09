# GrowDev Vercel Deployment - Verification & Deployment Checklist

## Critical Security Fixes ✅ COMPLETED

### Fixed Issues

- [x] **Hardcoded Gemini API Key Removed**
  - Removed: `AIzaSyCWK2g9CeDcvQThgaHdWKSIwvJRbbV-Ib8`
  - Replaced with: OpenRouter environment variables
  - Files updated:
    - `resources/js/services/openrouterAI.js` (NEW)
    - `resources/js/services/geminiAI.js` (now a compatibility wrapper)
    - `scripts/list-gemini-models.js`
    - `scripts/list-gemini-models-fetch.js`
  - **Note**: Gemini API completely replaced with OpenRouter

- [x] **Unsafe Serialization Fixed**
  - File: `app/Services/PasswordResetService.php`
  - Changed from: `unserialize($serialized)`
  - Changed to: `json_decode($jsonEncoded, associative: true)`
  - Prevents: PHP object injection vulnerabilities

- [x] **Package Version Constraints Updated**
  - File: `composer.json`
  - Changed from: `"barryvdh/laravel-dompdf": "*"`
  - Changed to: `"barryvdh/laravel-dompdf": "^3.0"`
  - Prevents: Uncontrolled version updates

---

## Configuration Files Created ✅ COMPLETED

| File | Purpose | Status |
|------|---------|--------|
| `vercel.json` | Vercel build configuration | ✅ Created |
| `.vercelignore` | Exclude Laravel files from Vercel | ✅ Created |
| `.env.example` | Updated with `VITE_OPENROUTER_API_KEY` | ✅ Updated |
| `.env.production` | Production environment template | ✅ Created |
| `config/cors.php` | CORS configuration for API | ✅ Created |
| `Procfile` | Removed (no longer needed for Vercel) | ❌ Removed |

| `docs/VERCEL_DEPLOYMENT.md` | Complete deployment guide | ✅ Created |

---

## Pre-Deployment Tasks (User Action Required)

### 1. OpenRouter API Setup ⚠️ CRITICAL

Get an OpenRouter API key to enable AI task generation:

```bash
# Do this before deploying:
1. Go to: https://openrouter.ai
2. Sign up with email or GitHub
3. Go to: https://openrouter.ai/keys
4. Click "Create new key"
5. Copy the key (starts with sk-or-v1-)
6. Go to: https://openrouter.ai/account/billing/overview
7. Add credits ($5+ recommended for testing)
8. Store key in `.env.local` for local development:
   VITE_OPENROUTER_API_KEY=sk-or-v1-xxxxx
9. Add to Vercel project settings:
   Environment Variables → Add VITE_OPENROUTER_API_KEY=sk-or-v1-xxxxx
```

**OpenRouter Models:**
- Default: `openai/gpt-3.5-turbo` (fast, cheap)
- Best: `openai/gpt-4-turbo` (slower, better quality)
- Budget: `anthropic/claude-3-haiku` (fastest, cheapest)
- Full list: https://openrouter.ai/docs#models

### 2. Update Environment Variables

Update these in your `.env.local` (local development):
```bash
VITE_GEMINI_API_KEY=your-new-gemini-key
VITE_API_URL=http://localhost:8000
GEMINI_API_KEY=your-new-gemini-key
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:5173
```

### 3. Test Locally

```bash
# Install dependencies
npm install
composer install

# Set up environment
cp .env.example .env.local
# Edit .env.local with new Gemini key and API URL

# Test frontend build
npm run build

# Verify build directory
ls -la public/build/

# Test development server
npm run dev
# Should load at http://localhost:5173

# In separate terminal, run backend
php artisan serve
# Should run at http://localhost:8000

# Test API connectivity
# In browser console at http://localhost:5173:
fetch('http://localhost:8000/api/health')
  .then(r => r.json())
  .then(console.log)
```

---

## Frontend Deployment to Vercel

### Option 1: Via GitHub (Recommended)

1. **Push code to GitHub:**
   ```bash
   git add .
   git commit -m "chore: prepare for Vercel deployment

   - Remove hardcoded API keys
   - Fix security vulnerabilities
   - Add deployment configurations
   - Update environment variables"
   git push origin main
   ```

2. **Deploy on Vercel:**
   - Go to https://vercel.com/new
   - Click "Continue with GitHub"
   - Select your GrowDev repository
   - Accept default settings (Vite detected automatically)
   - Click "Environment Variables" and add:
     - `VITE_GEMINI_API_KEY` = your-new-key
     - `VITE_API_URL` = https://api.yourdomain.com (or your backend URL)
   - Click "Deploy"

3. **Wait for deployment to complete** (~2-5 minutes)

### Option 2: Via Vercel CLI

```bash
# Install Vercel CLI
npm install -g vercel

# Login to Vercel
vercel login

# Deploy from project directory
vercel --prod

# Add environment variables when prompted
```

### Verification After Frontend Deployment

- [ ] Vercel shows "Ready" status
- [ ] Frontend loads at `https://yourdomain.vercel.app` (or Vercel domain)
- [ ] No console errors in browser DevTools
- [ ] CSS/Tailwind styles are applied
- [ ] All images and fonts load correctly
- [ ] Navigation works without 404 errors

---

## Backend Deployment (Choose One)

### Option 1: Heroku

```bash
# Deploy to Vercel (backend will be served directly)
# No separate backend deployment needed for integrated Laravel/Vercel setup

# Set environment variables in Vercel dashboard:
#   APP_ENV=production
#   APP_DEBUG=false
#   DB_CONNECTION=pgsql
#   DB_HOST=your-project.supabase.co
#   DB_PORT=5432
#   DB_DATABASE=postgres
#   DB_USERNAME=postgres
#   DB_PASSWORD=your-supabase-password
#   DB_SSL_MODE=require
#   GEMINI_API_KEY=your-openrouter-key (from OpenRouter, not Gemini)
#   VITE_SUPABASE_URL=your-project.supabase.co
#   VITE_SUPABASE_PUBLISHABLE_KEY=your-anon-public-key
#   CORS_ALLOWED_ORIGINS=https://yourdomain.vercel.app,https://yourdomain.com

# Deploy
vercel --prod

# Run migrations (after deployment)
# You can run these via Vercel dashboard or SSH if needed
php artisan migrate:fresh --seed

# View logs
vercel logs
```



---

## Post-Deployment Configuration

### 1. Test API Connectivity

From your Vercel frontend console:

```javascript
// In browser console at https://yourdomain.vercel.app
fetch('https://api.yourdomain.com/api/health')
  .then(r => r.text())
  .then(console.log)
  .catch(e => console.error('CORS or connection error:', e))

// Should output: "OK"
```

### 2. Verify Database

```bash
# SSH into backend and check database
heroku ps:exec php artisan tinker

# In Tinker:
DB::table('users')->first();
# Should return your admin user
```

### 3. Test Authentication

1. Open Vercel frontend
2. Navigate to login/register
3. Create new account or login with: `admin@growdev.com` / `password`
4. Verify JWT token in localStorage: `localStorage.getItem('auth_token')`
5. Create a task, assign it, change status
6. Verify notifications are received

### 4. Monitor Logs

```bash
# Heroku logs
heroku logs --tail
```

---

## Going Live Checklist

### Security ✅
- [x] All API keys removed from codebase
- [x] Unsafe serialization fixed
- [x] Package versions pinned
- [x] All Gemini API references replaced with OpenRouter
- [ ] `.env.production` configured with real values
- [ ] CORS configured for frontend domain
- [ ] Rate limiting enabled (optional)
- [ ] Error tracking configured (Sentry, etc.)

### Frontend ✅
- [ ] Vercel deployment successful
- [ ] Environment variables set correctly
- [ ] Frontend loads without errors
- [ ] API calls use correct backend URL
- [ ] Authentication flow works
- [ ] All pages and features functional

### Backend ✅
- [ ] Backend deployment successful
- [ ] Database migrations completed
- [ ] Environment variables configured
- [ ] CORS headers present in API responses
- [ ] Queue worker running (if needed)
- [ ] Database backups enabled
- [ ] Monitoring/logging configured

### Integration ✅
- [ ] Frontend ↔ Backend API communication working
- [ ] Authentication (login/register/logout) working
- [ ] Create/update/delete operations working
- [ ] Notifications being delivered
- [ ] Background jobs processing (if using queue)
- [ ] No console errors or warnings

### Monitoring ✅
- [ ] Error tracking enabled (Sentry, Rollbar, etc.)
- [ ] Application logs accessible
- [ ] Uptime monitoring configured (UptimeRobot, etc.)
- [ ] Performance monitoring enabled
- [ ] Database monitoring enabled
- [ ] Alerts configured for critical issues

---

## Important Notes

### API Key Management

OpenRouter is now configured:
1. **API Key**: Obtained from https://openrouter.ai/keys
2. **Credits**: Added to account at https://openrouter.ai/account/billing/overview
3. **Model**: Set in environment variables (default: openai/gpt-3.5-turbo)

Never commit API keys to version control. Use environment variables exclusively.

### Database Selection

Choose a managed database service:
- **PostgreSQL**: Neon (free, auto-scaling), AWS RDS, Google Cloud SQL
- **MySQL**: PlanetScale (free, serverless), AWS RDS, Google Cloud SQL

Connection string format:
```
postgres://user:password@host:5432/growdev
mysql://user:password@host:3306/growdev
```

### Queue Workers

For background notifications and task processing:
- **Development**: `php artisan queue:work --timeout=0`
- **Production**: Use platform-specific worker (Heroku dynos, etc.)

Without a queue worker, notifications won't be sent immediately.

### CORS Configuration

The `config/cors.php` allows:
- Specific domains: `https://yourdomain.vercel.app`
- Wildcard patterns: `#^https://.*\.vercel\.app$#`
- Multiple origins via `CORS_ALLOWED_ORIGINS` env variable

Update for your actual frontend domain.

---

## Troubleshooting

### Build Issues
- Run `npm install` locally to resolve dependency issues
- Check Node.js version: `node -v` (should be 18+)
- Clear cache: `rm -rf node_modules package-lock.json && npm install`

### API Connection Issues
- Verify `VITE_API_URL` environment variable is set
- Check backend CORS includes frontend domain
- Look for CORS errors in browser console
- Verify backend is running and accessible

### Database Connection Issues
- Test locally: `php artisan tinker` → `DB::connection()->getPdo()`
- Verify credentials in `.env`
- Check database exists and is accessible
- Review database provider logs

### Permission Issues
- Ensure `storage/` and `bootstrap/cache/` are writable
- Fix permissions: `chmod -R 775 storage bootstrap/cache`

---

## Support & Resources

- **Vercel Docs**: https://vercel.com/docs
- **Laravel Docs**: https://laravel.com/docs/12.x

- **Heroku Docs**: https://devcenter.heroku.com/
- **OpenRouter API Docs**: https://openrouter.ai/docs

---

## Summary of Changes

**Files Modified:**
- `resources/js/services/openrouterAI.js` - New OpenRouter implementation
- `resources/js/services/geminiAI.js` - Now a compatibility wrapper for openrouterAI
- `app/Services/PasswordResetService.php` - Fixed unsafe serialization
- `composer.json` - Updated package version constraints
- `.env.example` - Added `VITE_OPENROUTER_API_KEY` and `VITE_OPENROUTER_MODEL`

**Files Created:**
- `vercel.json` - Vercel configuration
- `.vercelignore` - Exclude files from Vercel
- `.env.production` - Production environment template
- `config/cors.php` - CORS configuration
- `Procfile` - Heroku deployment

- `docs/VERCEL_DEPLOYMENT.md` - Complete deployment guide

**Next Steps:**
1. Rotate Gemini API key immediately
2. Test locally with `npm run build`
3. Deploy frontend to Vercel
4. Deploy backend to Heroku
5. Configure environment variables
6. Run database migrations
7. Test authentication and API connectivity
8. Enable monitoring and logging
9. Monitor application for 24-48 hours after launch

---

**Last Updated:** May 2, 2026
**Status:** Ready for Deployment
**Critical Actions Needed:** Rotate Gemini API key (see section above)
