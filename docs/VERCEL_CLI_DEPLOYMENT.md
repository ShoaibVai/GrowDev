# GrowDev Vercel CLI Deployment Guide

## Quick Deploy (Recommended)

### On Windows:
```bash
# Make sure you're in the project directory
cd c:\Users\Endow_Corp\Documents\GitHub\GrowDev

# Run the deployment script
scripts\deploy-vercel.bat
```

### On Mac/Linux:
```bash
# Make sure you're in the project directory
cd ~/path/to/GrowDev

# Make script executable
chmod +x scripts/deploy-vercel.sh

# Run the deployment script
./scripts/deploy-vercel.sh
```

## Manual Deployment (Step-by-Step)

If you prefer to run commands manually, follow these steps:

### 1. Verify Vercel CLI & Login

```bash
# Check if Vercel CLI is installed
vercel --version

# If not installed:
npm install -g vercel

# Login to Vercel (opens browser)
vercel login

# Verify login
vercel whoami
```

### 2. Build Frontend

```bash
# Install dependencies
npm install

# Build production version
npm run build

# Verify build output
ls public/build/
```

### 3. Configure Environment Variables

Before deploying, you need to set these environment variables in Vercel.

**Option A: Set via Vercel Dashboard (Easiest)**

1. Go to https://vercel.com/dashboard
2. Select your project (or create new)
3. Go to Settings → Environment Variables
4. Add these variables for `Production`:
   - `VITE_API_URL` = `https://your-backend-url.com` (or `https://growdev-backend.herokuapp.com`)
   - `VITE_GEMINI_API_KEY` = `your-new-gemini-key`

**Option B: Set via CLI**

```bash
# Before linking project, set variables:
export VITE_API_URL=https://your-backend-url.com
export VITE_GEMINI_API_KEY=your-new-gemini-key

# Or on Windows PowerShell:
$env:VITE_API_URL = "https://your-backend-url.com"
$env:VITE_GEMINI_API_KEY = "your-new-gemini-key"

# Or on Windows Command Prompt:
set VITE_API_URL=https://your-backend-url.com
set VITE_GEMINI_API_KEY=your-new-gemini-key
```

### 4. Link to Vercel Project

```bash
# First time linking project
vercel link --project-name=growdev

# Follow prompts:
# - Set up and deploy? → Yes
# - Which scope? → Select your account
# - Found project settings? → Create new project
# - Project name? → growdev (or your choice)
# - Overwite settings? → Yes
```

### 5. Set Environment Variables in Vercel

After linking, set environment variables:

```bash
# Add backend API URL
vercel env add VITE_API_URL
# Paste the URL and press Enter

# Add Gemini API key
vercel env add VITE_GEMINI_API_KEY
# Paste the key and press Enter
```

### 6. Deploy to Production

```bash
# Deploy to production
vercel --prod

# This will:
# - Build your frontend
# - Upload to Vercel edge network
# - Assign production domain
# - Show deployment URL
```

### 7. Verify Deployment

```bash
# View deployment logs
vercel logs

# List recent deployments
vercel ls

# View in browser
open https://growdev.vercel.app
# or your custom domain
```

## Configuration Details

### vercel.json Settings

The `vercel.json` file in the project root configures:

```json
{
  "buildCommand": "npm run build",      // Build command
  "outputDirectory": "public/build",    // Output folder
  "framework": "vite",                  // Framework (auto-detected)
  "env": {                              // Environment variables
    "VITE_GEMINI_API_KEY": "@vite_gemini_api_key"
  },
  "regions": ["iad1"],                  // Deploy region (Virginia)
  "headers": [...]                      // Security headers
}
```

### .vercelignore Settings

Files excluded from Vercel deployment:
- All PHP/Laravel files
- Composer files
- Docker configs
- Development scripts
- IDE configurations

This keeps deployments small and fast.

## Environment Variables Reference

| Variable | Required | Example | Notes |
|----------|----------|---------|-------|
| `VITE_API_URL` | Yes | `https://api.example.com` | Backend API base URL |
| `VITE_GEMINI_API_KEY` | Yes | `AIza...` | New Gemini API key (NOT the exposed one) |

## Deployment Checklist

Before running deployment:

- [ ] Node.js 18+ installed (`node -v`)
- [ ] Vercel CLI installed (`vercel --version`)
- [ ] Logged into Vercel (`vercel whoami`)
- [ ] Backend URL ready (Heroku/Railway/Fly.io)
- [ ] New Gemini API key generated (old one revoked)
- [ ] All code changes committed to git
- [ ] `npm run build` works locally

## Post-Deployment Testing

After deployment completes:

### 1. Test Frontend Load
```bash
# In browser, open deployment URL
# Check for:
# - Page loads without errors
# - CSS/Tailwind styles applied
# - Images and fonts loaded
# - No console errors
```

### 2. Test API Connectivity
```javascript
// In browser console at deployment URL:
fetch('https://your-backend-url/api/health')
  .then(r => r.text())
  .then(console.log)
  .catch(e => console.error('Error:', e))

// Should output: "OK"
```

### 3. Test Authentication
```javascript
// Try login/register at frontend
// Verify:
// - Authentication works
// - JWT token stored in localStorage
// - API calls include Authorization header
// - Sanctum CSRF cookies working
```

### 4. Check Performance
In Vercel Dashboard:
- View deployment analytics
- Check response times
- Monitor error rates
- Review bandwidth usage

## Troubleshooting

### Build Fails

```bash
# Clear cache and rebuild
rm -rf node_modules package-lock.json
npm install
npm run build

# Check for errors
npm run build 2>&1 | tail -50
```

### API Calls Fail (CORS)

```javascript
// Check browser console for CORS error
// If CORS error occurs:
// 1. Verify backend CORS config includes frontend domain
// 2. Check VITE_API_URL environment variable is set
// 3. Ensure backend is running and accessible
```

**Update backend CORS:**
```bash
# On backend server (e.g., Heroku)
heroku config:set CORS_ALLOWED_ORIGINS=https://growdev.vercel.app
```

### Deployment Stuck

```bash
# Cancel current deployment
vercel cancel

# Check deployment status
vercel ls

# View error logs
vercel logs --error

# Redeploy
vercel --prod
```

### Environment Variables Not Applied

```bash
# Verify variables are set
vercel env ls

# Redeploy to apply new variables
vercel --prod --force

# Or clear cache
vercel env pull  # Pull environment variables locally
vercel --prod --force
```

## Advanced: Custom Domain

To use your own domain instead of `vercel.app`:

```bash
# Add domain to Vercel project
vercel domains add yourdomain.com

# Add DNS records (see Vercel dashboard for details)
# Then verify
vercel domains verify yourdomain.com

# Deploy
vercel --prod
```

## Monitoring & Logs

```bash
# View real-time logs
vercel logs --follow

# View logs for specific deployment
vercel logs https://growdev.vercel.app

# View error logs only
vercel logs --error

# View function logs (if using serverless functions)
vercel logs --function my-function
```

## Redeployment

To redeploy after making code changes:

```bash
# Push changes to git
git add .
git commit -m "Update: description of changes"
git push origin main

# Vercel auto-deploys on push (if set up)
# OR manually redeploy
vercel --prod

# Or promote preview to production
vercel promote https://preview-url.vercel.app
```

## Rollback

To roll back to a previous deployment:

```bash
# List deployments
vercel ls

# Promote a previous deployment to production
vercel promote https://previous-deployment-url.vercel.app
```

## Cost & Limits

**Free Plan:**
- 5 concurrent deployments
- 100GB/month bandwidth
- 50 Serverless Function invocations
- 100 Deployments/month

**Pro Plan:** $20/month
- Unlimited concurrent deployments
- Unlimited bandwidth
- Unlimited function invocations

## Support

- Vercel Docs: https://vercel.com/docs
- Vercel Community: https://github.com/vercel/vercel/discussions
- Email: support@vercel.com

---

**Next: Deploy Backend**

After the frontend is deployed to Vercel, deploy the backend to Heroku, Fly.io, or Railway. See `docs/VERCEL_DEPLOYMENT.md` for backend-specific instructions.
