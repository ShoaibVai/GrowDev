@echo off
REM GrowDev Vercel Frontend Deployment Script (Windows)
REM This script automates the deployment process using Vercel CLI
REM 
REM Prerequisites:
REM - Vercel CLI installed: npm install -g vercel
REM - Logged in to Vercel: vercel login
REM - Node.js 18+ installed
REM - Environment variables set as system variables or .env file

setlocal enabledelayedexpansion

echo.
echo 🚀 GrowDev Vercel Frontend Deployment
echo ======================================
echo.

REM Configuration - UPDATE THESE VALUES
set VERCEL_PROJECT_NAME=growdev
set BACKEND_API_URL=%VITE_API_URL%
if "!BACKEND_API_URL!"=="" set BACKEND_API_URL=https://api.yourdomain.com
set OPENROUTER_API_KEY=%VITE_OPENROUTER_API_KEY%
if "!OPENROUTER_API_KEY!"=="" (
    echo ✗ ERROR: VITE_OPENROUTER_API_KEY not set. Set it as an environment variable.
    exit /b 1
)
set OPENROUTER_MODEL=%VITE_OPENROUTER_MODEL%
if "!OPENROUTER_MODEL!"=="" set OPENROUTER_MODEL=openai/gpt-3.5-turbo
set VERCEL_ENV=%1
if "!VERCEL_ENV!"=="" set VERCEL_ENV=production

echo Configuration:
echo   Project Name: !VERCEL_PROJECT_NAME!
echo   Backend URL: !BACKEND_API_URL!
echo   OpenRouter Model: !OPENROUTER_MODEL!
echo   Environment: !VERCEL_ENV!
echo.

REM Step 1: Verify Vercel CLI is installed
echo [1/7] Checking Vercel CLI...
where vercel >nul 2>nul
if errorlevel 1 (
    echo ✗ Vercel CLI not found. Install with: npm install -g vercel
    exit /b 1
)
echo ✓ Vercel CLI installed
echo.

REM Step 2: Verify logged in
echo [2/7] Checking Vercel authentication...
vercel whoami >nul 2>nul
if errorlevel 1 (
    echo ⚠ Not logged in to Vercel. Running login...
    call vercel login
)
for /f "tokens=*" %%i in ('vercel whoami 2^>nul') do set VERCEL_USER=%%i
echo ✓ Logged in as: !VERCEL_USER!
echo.

REM Step 3: Install Node dependencies
echo [3/7] Installing Node dependencies...
if exist "node_modules" (
    echo   Node modules already installed, skipping...
) else (
    call npm install
)
echo ✓ Dependencies installed
echo.

REM Step 4: Build frontend
echo [4/7] Building frontend (npm run build)...
call npm run build
if exist "public\build" (
    echo ✓ Build successful
    echo   Output directory: public\build\
) else (
    echo ✗ Build failed - public\build directory not found
    exit /b 1
)
echo.

REM Step 5: Link/Create Vercel project
echo [5/7] Linking Vercel project...
if exist ".vercel\project.json" (
    echo   Project already linked, skipping...
) else (
    echo   Running: vercel link --project-name=!VERCEL_PROJECT_NAME!
    call vercel link --project-name="!VERCEL_PROJECT_NAME!"
)
echo ✓ Project linked
echo.

REM Step 6: Set environment variables in Vercel
echo [6/7] Setting environment variables in Vercel...
echo   Setting VITE_API_URL...
echo !BACKEND_API_URL! | vercel env add VITE_API_URL --environment=production >nul 2>nul
echo   ✓ VITE_API_URL set

echo   Setting VITE_OPENROUTER_API_KEY...
echo !OPENROUTER_API_KEY! | vercel env add VITE_OPENROUTER_API_KEY --environment=production >nul 2>nul
echo   ✓ VITE_OPENROUTER_API_KEY set

echo   Setting VITE_OPENROUTER_MODEL...
echo !OPENROUTER_MODEL! | vercel env add VITE_OPENROUTER_MODEL --environment=production >nul 2>nul
echo   ✓ VITE_OPENROUTER_MODEL set
echo.

REM Step 7: Deploy
echo [7/7] Deploying to Vercel (!VERCEL_ENV!)...
if "!VERCEL_ENV!"=="production" (
    echo   Running: vercel --prod
    call vercel --prod
) else (
    echo   Running: vercel (preview deployment)
    call vercel
)
echo.

echo ✓ Deployment successful!
echo.
echo 📊 Deployment Summary:
echo   Project: !VERCEL_PROJECT_NAME!
echo   Environment: !VERCEL_ENV!
echo   Backend API: !BACKEND_API_URL!
echo.
echo Next Steps:
echo   1. Check Vercel dashboard for deployment status
echo   2. Test API connectivity in browser console
echo   3. Test authentication (login/register)
echo   4. Monitor logs: vercel logs
echo.
echo 📝 View deployment:
echo   Dashboard: https://vercel.com/!VERCEL_USER!/!VERCEL_PROJECT_NAME!
echo   Logs: vercel logs
echo   Rebuild: vercel --prod
echo.

endlocal
