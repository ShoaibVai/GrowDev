# Vercel Deployment Quick Start

## TL;DR - Deploy in 5 Minutes

### Prerequisites Checklist
- [ ] Vercel account (https://vercel.com)
- [ ] Logged into Vercel CLI locally (`vercel login`)
- [ ] Backend URL ready (e.g., `https://growdev-backend.herokuapp.com`)
- [ ] New Gemini API key generated and old one revoked
- [ ] Node.js 18+ installed

### Quick Deploy

**Windows:**
```cmd
cd c:\Users\Endow_Corp\Documents\GitHub\GrowDev
scripts\deploy-vercel.bat
```

**Mac/Linux:**
```bash
cd ~/path/to/GrowDev
chmod +x scripts/deploy-vercel.sh
./scripts/deploy-vercel.sh
```

The script will:
1. ✓ Verify Vercel CLI
2. ✓ Check authentication
3. ✓ Install dependencies
4. ✓ Build frontend
5. ✓ Link Vercel project
6. ✓ Set environment variables
7. ✓ Deploy to production

**Deployment takes 2-5 minutes.**

---

## Step-by-Step Manual Deployment

If you prefer to do this manually, follow these exact commands:

### Step 1: Prepare Your System

```bash
# Open terminal/PowerShell in project directory
cd c:\Users\Endow_Corp\Documents\GitHub\GrowDev

# Verify Node.js version
node -v
# Should output: v18.0.0 or higher

# Verify npm version
npm -v
# Should output: 9.0.0 or higher
```

### Step 2: Verify Vercel CLI & Login

```bash
# Check if Vercel CLI is installed
vercel --version

# If not installed, install it globally:
npm install -g vercel

# Login to Vercel (will open browser)
vercel login

# Verify you're logged in
vercel whoami
```

### Step 3: Install & Build

```bash
# Install Node dependencies
npm install

# Build the frontend
npm run build

# Verify build succeeded
ls public/build/
# Should show many files (CSS, JS, images, etc.)
```

### Step 4: Prepare Configuration

Before deploying, decide your configuration:

**Option A: Use Environment Variables (Recommended)**

Create a `.env.vercel` file with your deployment settings:

```bash
# Create the file
cat > .env.vercel << EOF
VITE_API_URL=https://your-backend-url.com
VITE_GEMINI_API_KEY=your-new-gemini-api-key
EOF

# Verify file created
cat .env.vercel
```

**Option B: Use Vercel Dashboard**

Set variables directly in https://vercel.com/dashboard:
1. Select project
2. Settings → Environment Variables
3. Add `VITE_API_URL` and `VITE_GEMINI_API_KEY`

### Step 5: Link to Vercel

```bash
# Link project to Vercel
vercel link

# Follow the prompts:
# ? Set up and deploy? → Yes
# ? Which scope? → Select your account
# ? Found project settings? → Yes, overwrite
# ? Project name? → growdev (or your choice)
```

### Step 6: Set Environment Variables

```bash
# Method 1: Via CLI (interactive)
vercel env add VITE_API_URL

# Paste: https://your-backend-url.com
# Press Enter

vercel env add VITE_GEMINI_API_KEY

# Paste: your-new-gemini-api-key
# Press Enter
```

### Step 7: Deploy to Production

```bash
# Deploy to production
vercel --prod

# Wait for deployment to complete
# You'll see:
# ✓ Production URL: https://growdev.vercel.app
# ✓ Deployment Complete
```

### Step 8: Verify Deployment

```bash
# View logs
vercel logs

# Check if variables are set
vercel env ls

# List deployments
vercel ls
```

Open your browser to the deployment URL and test!

---

## Configuration Values You Need

Replace these with your actual values:

| Value | Where to Get | Example |
|-------|--------------|---------|
| `VITE_API_URL` | Your backend server (if separate) or leave blank for integrated | (optional) |
| `VITE_GEMINI_API_KEY` | OpenRouter.ai (not Google AI Studio) | `sk-or-v1-...` |

### Get Backend URL (if using separate backend)

With the integrated Laravel/Vercel setup, your backend is served directly by Vercel, so `VITE_API_URL` is typically not needed. If you have a separate backend:

```bash
# Get your Vercel backend URL from dashboard after deployment
# Example: https://your-project.vercel.app
```

### Get OpenRouter API Key (replaces Gemini)

1. Go to https://openrouter.ai/
2. Sign up and create an API key
2. Click "Get API Key"
3. Click "Create API key in new project"
4. Copy the key
5. Use in `VITE_GEMINI_API_KEY`

---

## What Happens After Deployment

✅ Frontend is live on Vercel
✅ Environment variables are configured
✅ API calls will use your backend URL
✅ Gemini AI features use new API key

### Test It Works

**In browser console (at your Vercel URL):**

```javascript
// Test 1: API connectivity
fetch(import.meta.env.VITE_API_URL + '/api/health')
  .then(r => r.text())
  .then(console.log)
  .catch(e => console.error('API Error:', e))

// Test 2: Gemini API key loaded
console.log('Gemini key:', import.meta.env.VITE_GEMINI_API_KEY ? '✓ Set' : '✗ Not set')

// Test 3: Backend URL loaded
console.log('Backend URL:', import.meta.env.VITE_API_URL)
```

---

## Troubleshooting

### Issue: "Vercel CLI not found"
```bash
npm install -g vercel
```

### Issue: "Not authenticated"
```bash
vercel login
```

### Issue: Build fails
```bash
# Clear everything and rebuild
rm -rf node_modules package-lock.json
npm install
npm run build
```

### Issue: API calls failing (CORS)
1. Check `VITE_API_URL` is correct
2. Verify backend CORS includes your Vercel domain
3. Ensure backend is running

Update backend CORS:
```bash
# For Heroku
heroku config:set CORS_ALLOWED_ORIGINS=https://growdev.vercel.app -a growdev-backend
```

### Issue: Environment variables not applied
```bash
# Pull environment
vercel env pull

# Redeploy with force
vercel --prod --force
```

---

## Useful Commands

```bash
# View deployment
vercel ls

# View logs (real-time)
vercel logs --follow

# View errors
vercel logs --error

# Promote preview to production
vercel promote https://preview-url.vercel.app

# Rollback to previous
vercel ls  # Find previous URL
vercel promote https://previous-url.vercel.app

# Force rebuild
vercel --prod --force

# Check environment variables
vercel env ls

# Remove a deployment
vercel rm https://your-url.vercel.app

# Get project info
vercel project
```

---

## Architecture After Deployment

```
┌─────────────────────────────────────────┐
│       You (Developer's Browser)          │
└──────────────┬──────────────────────────┘
               │ HTTPS
               │
    ┌──────────▼──────────┐
    │    Vercel Edge      │
    │   (Worldwide CDN)   │
    │                     │
    │ - Frontend Assets   │
    │ - Static Files      │
    │ - JS/CSS/Images     │
    │                     │
    │ https://growdev.    │
    │ vercel.app          │
    └──────────┬──────────┘
               │
    ┌──────────▼──────────────────────┐
    │    Your Backend Server          │
    │  (Heroku/Railway)        │
    │                                 │
    │ - Laravel Application           │
    │ - Database                      │
    │ - Queue Workers                 │
    │ - Business Logic                │
    │                                 │
    │ https://growdev-backend.        │
    │ herokuapp.com                   │
    └─────────────────────────────────┘
```

Frontend and backend are separate but work together!

---

## Next Steps

1. **Run deployment script** (5 minutes)
2. **Test in browser** (2 minutes)
3. **Deploy backend** to Heroku (see VERCEL_DEPLOYMENT.md)
4. **Connect frontend to backend** (already configured!)
5. **Monitor and optimize**

---

## When to Use Each Document

- **VERCEL_CLI_DEPLOYMENT.md** - Manual step-by-step CLI commands
- **VERCEL_DEPLOYMENT.md** - Complete deployment guide (frontend + backend)
- **DEPLOYMENT_CHECKLIST.md** - Pre/post deployment verification

---

**Status:** Ready to Deploy ✓
**Time Estimate:** 5-10 minutes (frontend), 15-30 minutes (backend)
**Difficulty:** Easy (scripts do most of the work)
