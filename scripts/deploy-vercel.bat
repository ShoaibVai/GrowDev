@echo off
REM GrowDev Vercel Full-Stack Deployment Script (Windows)
REM Deploys Laravel backend + Vite frontend as a single Vercel project
REM
REM Prerequisites:
REM - Vercel CLI installed: npm install -g vercel
REM - Logged in to Vercel: vercel login
REM - PHP 8.2+ with Composer installed locally

setlocal enabledelayedexpansion

echo.
echo ======================================
echo   GrowDev Vercel Full-Stack Deploy
echo ======================================
echo.

set VERCEL_PROJECT_NAME=growdev
set VERCEL_ENV=%1
if "!VERCEL_ENV!"=="" set VERCEL_ENV=production

echo Configuration:
echo   Project Name: !VERCEL_PROJECT_NAME!
echo   Environment: !VERCEL_ENV!
echo.

REM Step 1: Verify Vercel CLI
echo [1/8] Checking Vercel CLI...
where vercel >nul 2>nul
if errorlevel 1 (
    echo ✗ Vercel CLI not found. Install with: npm install -g vercel
    exit /b 1
)
echo ✓ Vercel CLI installed
echo.

REM Step 2: Verify logged in
echo [2/8] Checking Vercel authentication...
vercel whoami >nul 2>nul
if errorlevel 1 (
    echo ⚠ Not logged in. Running login...
    call vercel login
)
for /f "tokens=*" %%i in ('vercel whoami 2^>nul') do set VERCEL_USER=%%i
echo ✓ Logged in as: !VERCEL_USER!
echo.

REM Step 3: Install Composer dependencies
echo [3/8] Installing Composer dependencies...
where composer >nul 2>nul
if errorlevel 1 (
    echo ⚠ Composer not found locally. Will be handled by Vercel build.
) else (
    call composer install --no-dev --optimize-autoloader
    echo ✓ Composer dependencies installed
)
echo.

REM Step 4: Install Node dependencies
echo [4/8] Installing Node dependencies...
call npm install
echo ✓ Node dependencies installed
echo.

REM Step 5: Build frontend
echo [5/8] Building frontend assets...
call npm run build
if exist "public\build" (
    echo ✓ Build successful
    echo   Output: public\build\
) else (
    echo ✗ Build failed
    exit /b 1
)
echo.

REM Step 6: Link Vercel project
echo [6/8] Linking Vercel project...
if exist ".vercel\project.json" (
    echo   Project already linked
) else (
    call vercel link --project-name="!VERCEL_PROJECT_NAME!"
)
echo ✓ Project linked
echo.

REM Step 7: Note about environment variables
echo [7/8] Environment variables...
echo   Set these in Vercel dashboard before deploying:
echo   - APP_KEY, DB_CONNECTION, DB_HOST, DB_PORT
echo   - DB_DATABASE, DB_USERNAME, DB_PASSWORD
echo   - OPENROUTER_API_KEY, OPENROUTER_MODEL
echo   - SESSION_DRIVER=cookie, QUEUE_CONNECTION=sync
echo   - LOG_CHANNEL=stderr, CACHE_STORE=database
echo   - FILESYSTEM_DISK=s3, AWS_* (if using S3)
echo ✓ Environment variables noted
echo.

REM Step 8: Deploy
echo [8/8] Deploying to Vercel (!VERCEL_ENV!)...
if "!VERCEL_ENV!"=="production" (
    call vercel --prod
) else (
    call vercel
)
echo.
echo ✓ Deployment complete!
echo.
echo   Dashboard: https://vercel.com/!VERCEL_USER!/!VERCEL_PROJECT_NAME!
echo   Logs: vercel logs
echo.
echo   Post-deploy: run migrations via GitHub Actions or locally
echo   with: php artisan migrate --force
echo.

endlocal
