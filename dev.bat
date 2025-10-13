@echo off
REM GrowDev Development Server Script
echo.
echo 🚀 GrowDev Development Mode
echo ==========================
echo.

REM Check if setup has been run
if not exist ".env" (
    echo ❌ Project not set up yet!
    echo    Please run setup.bat first
    pause
    exit /b 1
)

if not exist "vendor\" (
    echo ❌ Dependencies not installed!
    echo    Please run setup.bat first
    pause
    exit /b 1
)

echo 🔧 Preparing development environment...

REM Clear caches for development
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo ✅ Caches cleared

echo.
echo 🎨 Starting asset watcher in background...
start "Vite Dev Server" cmd /k "npm run dev"

echo ⏳ Waiting for Vite to start...
timeout /t 3 /nobreak >nul

echo.
echo 🌐 Starting Laravel development server...
echo    Frontend: http://localhost:5173 (Vite)
echo    Backend: http://localhost:8000 (Laravel)
echo    Application: http://localhost:8000
echo.
echo 💡 Both servers are running:
echo    - Press Ctrl+C here to stop Laravel server
echo    - Close the Vite window to stop asset watcher
echo.

php artisan serve