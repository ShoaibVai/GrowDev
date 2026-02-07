@echo off
setlocal enabledelayedexpansion

:: Change to the directory where this script lives
cd /d %~dp0

set "SCRIPT_NAME=GrowDev Project Setup"

echo ==============================================
echo   %SCRIPT_NAME%
echo ==============================================
echo.
echo This script will set up the GrowDev project on your machine.
echo.

:: ==================================================================
:: STEP 1: Check Prerequisites
:: ==================================================================

echo [Step 1] Checking prerequisites...
echo.

:: Check PHP
where php >nul 2>&1
if errorlevel 1 (
    echo [ERROR] PHP was not found on PATH.
    echo.
    echo Please install PHP 8.2+ from:
    echo   - Windows: https://windows.php.net/download/
    echo   - Or use Laragon: https://laragon.org/download/
    echo   - Or use XAMPP: https://www.apachefriends.org/
    echo.
    echo After installation, add PHP to your PATH and rerun this script.
    goto :end
) else (
    for /f "tokens=2" %%i in ('php -r "echo PHP_VERSION;"') do set PHP_VERSION=%%i
    echo [OK] PHP found
)

:: Check Composer
where composer >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Composer was not found on PATH.
    echo.
    echo Please install Composer from: https://getcomposer.org/download/
    echo After installation, rerun this script.
    goto :end
) else (
    echo [OK] Composer found
)

:: Check Node.js
where node >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Node.js was not found on PATH.
    echo.
    echo Please install Node.js 18+ from: https://nodejs.org/
    echo   Recommended: Node.js 24 LTS
    echo.
    echo Or install via Chocolatey (as Administrator):
    echo   choco install nodejs --version="24.13.0" -y
    echo.
    echo After installation, rerun this script.
    goto :end
) else (
    echo [OK] Node.js found
)

:: Check npm and fix execution policy if needed
where npm >nul 2>&1
if errorlevel 1 (
    echo [ERROR] npm was not found on PATH.
    echo This usually comes with Node.js. Please reinstall Node.js.
    goto :end
)

:: Test npm execution
npm -v >nul 2>&1
if errorlevel 1 (
    echo [WARN] npm cannot execute. Fixing PowerShell execution policy...
    powershell -Command "Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser -Force"
    echo [OK] Execution policy fixed
)
echo [OK] npm found

echo.
echo [OK] All prerequisites satisfied!
echo.

:: ==================================================================
:: STEP 2: Environment Configuration
:: ==================================================================

echo [Step 2] Configuring environment...
echo.

:: Copy .env if it does not already exist
if not exist ".env" (
    if exist ".env.example" (
        copy ".env.example" ".env" >nul
        if errorlevel 1 (
            echo [ERROR] Failed to copy .env.example to .env.
            goto :error
        ) else (
            echo [OK] Created .env from .env.example
        )
    ) else (
        echo [WARN] .env.example not found. Skipping .env creation.
    )
) else (
    echo [OK] .env already exists
)

echo.

:: ==================================================================
:: STEP 3: Database Setup
:: ==================================================================

echo [Step 3] Setting up database...
echo.

:: Ensure SQLite database file exists (default setup)
if not exist "database" mkdir "database" >nul
if not exist "database\database.sqlite" (
    type nul > "database\database.sqlite"
    if errorlevel 1 (
        echo [ERROR] Could not create database\database.sqlite. Check permissions.
        goto :error
    ) else (
        echo [OK] Created database\database.sqlite
    )
) else (
    echo [OK] database\database.sqlite already exists
)

echo.

:: ==================================================================
:: STEP 4: Install Dependencies
:: ==================================================================

echo [Step 4] Installing dependencies...
echo.

echo [Composer] Installing PHP packages...
call composer install --no-interaction --prefer-dist
if errorlevel 1 (
    echo [WARN] composer install failed. Trying composer update to resolve dependencies...
    call composer update --no-interaction
    if errorlevel 1 goto :error
)

echo.

echo [npm] Installing JavaScript packages...
call npm install --loglevel=error
if errorlevel 1 goto :error

echo.

:: ==================================================================
:: STEP 5: Build Assets
:: ==================================================================

echo [Step 5] Building frontend assets...
echo.

call npm run build
if errorlevel 1 goto :error

echo.

:: ==================================================================
:: STEP 6: Laravel Setup
::================================================================== 

echo [Step 6] Setting up Laravel...
echo.

:: Generate application key if missing (empty or default placeholder)
set "APP_KEY_FOUND="
for /f "tokens=1,* delims==" %%A in ('findstr /b "APP_KEY=" ".env"') do (
    set "APP_KEY_FOUND=%%B"
)
if "!APP_KEY_FOUND!"=="" (
    echo [Laravel] Generating application key...
    call php artisan key:generate --ansi
    if errorlevel 1 goto :error
) else (
    echo [OK] APP_KEY already set
)

echo.

:: ==================================================================
:: STEP 7: Database Migration & Seeding
:: ==================================================================

echo [Step 7] Running database migrations and seeders...
echo.

call php artisan migrate:fresh --seed --force
if errorlevel 1 goto :error

echo.

:: ==================================================================
:: SETUP COMPLETE
:: ==================================================================

echo ==============================================
echo   SETUP COMPLETE!
echo ==============================================
echo.
echo [SUCCESS] GrowDev is ready to use!
echo.
echo Default Admin Account:
echo   Email: admin@growdev.com
echo   Password: password
echo.
echo IMPORTANT - Configure your .env file:
echo   1. Set GEMINI_API_KEY for AI task generation
echo   2. Configure mail settings for notifications
echo   3. Adjust database settings if needed
echo.
echo To start development:
echo   composer dev    (runs everything: server + queue + logs + vite)
echo.
echo Or run individually:
echo   php artisan serve             - Start web server
echo   php artisan queue:work        - Start queue worker (required!)
echo   npm run dev                   - Start Vite dev server
echo.
echo.
echo [INFO] To start the server, run: php artisan serve
echo.
pause
goto :eof

:error
echo.
echo [ERROR] Setup failed. Please check the error messages above.
pause
exit /b 1

:end
pause

echo.

echo [Step] Clearing caches...
call php artisan optimize:clear
if errorlevel 1 goto :error

echo.
echo [Step] Seeding Database...
call php artisan migrate:fresh --seed
if errorlevel 1 goto :error

echo.

echo ==============================================
echo   %SCRIPT_NAME% - COMPLETE
echo.
echo   Admin Credentials:
echo     Email:    admin@growdev.com
echo     Password: password
echo.
echo   Next steps:
echo     1. php artisan serve
echo     2. npm run dev      ^<-- for Vite dev server
echo ==============================================

goto :end

:error
echo.
echo [ERROR] Setup failed with exit code %errorlevel%.
echo Please review the messages above, resolve the issue, and run setup.bat again.

goto :end

:end
echo.
pause
endlocal
