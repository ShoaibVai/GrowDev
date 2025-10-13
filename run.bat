@echo off
REM GrowDev Quick Run Script (Production Mode)
echo.
echo 🚀 GrowDev - Quick Start (Production Mode)
echo =========================================
echo.

REM Check if project is set up
if not exist ".env" (
    echo ❌ Project not set up! Please run setup.bat first
    pause
    exit /b 1
)

REM Check for dependencies
if not exist "vendor\" (
    echo 📦 Installing PHP dependencies...
    composer install --no-dev --optimize-autoloader
)

REM Check and build assets if needed
if not exist "public\build\manifest.json" (
    echo 🔨 Building production assets...
    npm run build
    
    if %errorlevel% neq 0 (
        echo ❌ Asset build failed
        pause
        exit /b 1
    )
    echo ✅ Assets built successfully
) else (
    echo ✅ Production assets found
)

REM Clear caches
echo 🧹 Optimizing application...
php artisan config:clear
php artisan route:clear

echo.
echo 🌐 Starting GrowDev server...
echo    Application: http://localhost:8000
echo    Mode: Production (using built assets)
echo    Press Ctrl+C to stop
echo.

php artisan serve