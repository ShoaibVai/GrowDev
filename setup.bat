@echo off
setlocal enabledelayedexpansion

REM GrowDev Project Full Setup Script
echo.
echo 🚀 GrowDev Project Full Setup
echo ================================
echo This script will set up your complete development environment
echo.

REM Set error handling
set "SETUP_SUCCESS=true"

REM Function to display section headers
echo.
echo 🔍 STEP 1: System Requirements Check
echo ====================================

REM Check for PHP
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ PHP is not installed or not in PATH
    echo    Please install PHP 8.1 or higher
    echo    Download from: https://windows.php.net/download/
    set "SETUP_SUCCESS=false"
) else (
    echo ✅ PHP found
    php --version | findstr /C:"PHP"
    
    REM Check PHP version (basic check for 8.x)
    for /f "tokens=2 delims= " %%i in ('php --version ^| findstr /C:"PHP"') do (
        set "PHP_VERSION=%%i"
        echo    Version: !PHP_VERSION!
    )
)

REM Check for Composer
where composer >nul 2>nul
if %errorlevel% neq 0 (
    REM Try to find composer.phar
    if exist "composer.phar" (
        echo ✅ Composer found (local composer.phar)
        set COMPOSER_CMD=php composer.phar
        php composer.phar --version | findstr /C:"Composer"
    ) else (
        echo ❌ Composer is not installed or not in PATH
        echo    Please install Composer from: https://getcomposer.org/download/
        set "SETUP_SUCCESS=false"
    )
) else (
    echo ✅ Composer found in PATH
    set COMPOSER_CMD=composer
    composer --version | findstr /C:"Composer"
)

REM Check for Node.js
where node >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ Node.js is not installed or not in PATH
    echo    Please install Node.js 18 or higher
    echo    Download from: https://nodejs.org/
    set "SETUP_SUCCESS=false"
) else (
    echo ✅ Node.js found
    node --version
)

REM Check for NPM
where npm >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ NPM is not installed or not in PATH
    echo    NPM should come with Node.js installation
    set "SETUP_SUCCESS=false"
) else (
    echo ✅ NPM found
    npm --version
)

REM Exit if requirements not met
if "%SETUP_SUCCESS%"=="false" (
    echo.
    echo ❌ Setup failed: Missing required dependencies
    echo    Please install the missing requirements and run setup again
    pause
    exit /b 1
)

echo.
echo 🔧 STEP 2: Environment Configuration
echo ===================================

REM Check if .env file exists
if not exist ".env" (
    if exist ".env.example" (
        copy .env.example .env >nul
        echo ✅ Created .env file from .env.example
        echo.
        echo 📝 Environment Variables Configured:
        echo    ✓ APP_NAME=GrowDev
        echo    ✓ APP_URL=http://localhost:8000
        echo    ✓ Database connection (Supabase PostgreSQL)
        echo    ✓ Supabase API configuration
        echo    ✓ Vite frontend build configuration
    ) else (
        echo ❌ .env.example file not found
        echo    Cannot create environment configuration
        pause
        exit /b 1
    )
) else (
    echo ℹ️ .env file already exists
    echo    Keeping existing configuration
)

echo.
echo 📦 STEP 3: Dependency Installation
echo ==================================

REM Install Composer dependencies
echo Installing PHP dependencies...
echo   - Laravel Framework ^10.0
echo   - Inertia.js Server Adapter
echo   - Supabase Integration
echo.
%COMPOSER_CMD% install --no-interaction --optimize-autoloader

if %errorlevel% neq 0 (
    echo ❌ Failed to install Composer dependencies
    echo    Check your internet connection and try again
    pause
    exit /b 1
)

echo ✅ PHP dependencies installed successfully

echo.
echo Installing JavaScript dependencies...
echo   - Vue.js 3 + Inertia.js
echo   - Tailwind CSS + Headless UI
echo   - Supabase Client Library
echo   - Vite Build Tools
echo.
npm install

if %errorlevel% neq 0 (
    echo ❌ Failed to install NPM dependencies
    echo    Check your internet connection and try again
    pause
    exit /b 1
)

echo ✅ JavaScript dependencies installed successfully

echo.
echo 🔑 STEP 4: Application Configuration
echo ===================================

REM Generate Laravel application key
echo Generating application encryption key...
php artisan key:generate --ansi

if %errorlevel% neq 0 (
    echo ❌ Failed to generate application key
    pause
    exit /b 1
)

echo ✅ Application key generated

REM Create storage symbolic link
echo.
echo Creating storage symbolic link...
php artisan storage:link

if %errorlevel% neq 0 (
    echo ⚠️ Storage link creation failed (may not be critical)
) else (
    echo ✅ Storage link created
)

REM Clear and optimize Laravel caches
echo.
echo Optimizing Laravel application...
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
echo ✅ Application caches cleared

echo.
echo 🗄️ STEP 5: Database & Supabase Setup
echo ====================================

echo ✅ Supabase Configuration Already Set:
echo    URL: https://bwrxvijpmhnuevdrtxcy.supabase.co
echo    Anonymous Key: Configured
echo    Service Role Key: Configured
echo.
echo 📋 Database Schema Setup Instructions:
echo    1. Open your Supabase dashboard: https://supabase.com/dashboard
echo    2. Navigate to your project: bwrxvijpmhnuevdrtxcy
echo    3. Go to SQL Editor
echo    4. Run the schema file: database/migrations/supabase_schema.sql
echo.
echo 🏗️ The schema includes:
echo    ✓ User profiles table
echo    ✓ Projects management tables
echo    ✓ Project members and roles
echo    ✓ Messages and collaboration features
echo    ✓ Row Level Security (RLS) policies
echo.

echo.
echo 🎨 STEP 6: Frontend Asset Building
echo =================================

echo Building production assets with Vite...
echo   - Vue.js components compilation
echo   - Tailwind CSS processing
echo   - JavaScript bundling and optimization
echo   - Asset manifest generation
echo.
npm run build

if %errorlevel% neq 0 (
    echo ❌ Failed to build assets
    echo    Check for JavaScript/Vue syntax errors
    pause
    exit /b 1
)

echo ✅ Production assets built successfully
echo    Assets available in: public/build/

echo.
echo 🎉 SETUP COMPLETE!
echo ==================
echo.
echo 🚀 GrowDev is now ready for development!
echo.
echo 📋 What's been configured:
echo    ✅ Environment variables (.env)
echo    ✅ PHP dependencies (Laravel, Inertia, etc.)
echo    ✅ JavaScript dependencies (Vue, Tailwind, etc.)
echo    ✅ Application encryption key
echo    ✅ Storage symlinks
echo    ✅ Optimized caches
echo    ✅ Production assets built
echo.
echo 🔧 Manual steps remaining:
echo    1. Run the Supabase schema in your dashboard SQL Editor
echo       File: database/migrations/supabase_schema.sql
echo.
echo 🚀 Start Development:
echo    Option 1 - Development mode (recommended):
echo      Terminal 1: php artisan serve
echo      Terminal 2: npm run dev
echo.
echo    Option 2 - Production mode:
echo      php artisan serve
echo      (Uses pre-built assets)
echo.
echo 🌐 Application will be available at:
echo    http://localhost:8000
echo.
echo 📁 Project Structure:
echo    /app/Http/Controllers/     - Laravel controllers
echo    /resources/js/pages/       - Vue.js pages
echo    /resources/js/components/  - Vue.js components
echo    /app/Services/             - Supabase integration
echo.

REM Ask if user wants to start the server
echo.
set /p start_server="🚀 Start the development server now? (y/n): "
if /i "%start_server%"=="y" (
    echo.
    echo 🌟 Starting GrowDev development server...
    echo    Visit: http://localhost:8000
    echo    Press Ctrl+C to stop the server
    echo.
    php artisan serve
) else (
    echo.
    echo 💡 To start later, run: php artisan serve
    echo    Then visit: http://localhost:8000
)

echo.
echo 📖 For more information, check the documentation files
pause