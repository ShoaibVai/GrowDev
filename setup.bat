@echo off
setlocal enabledelayedexpansion

:: Change to the directory where this script lives
cd /d %~dp0

set "SCRIPT_NAME=GrowDev Project Setup"

echo ==============================================
echo   %SCRIPT_NAME%
echo ==============================================
echo.

:: Check required executables
where php >nul 2>&1
if errorlevel 1 (
    echo [ERROR] PHP was not found on PATH. Please install PHP 8.1+ and retry.
    goto :end
)

where composer >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Composer was not found on PATH. Please install Composer and retry.
    goto :end
)

where npm >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Node.js/npm were not found on PATH. Please install Node.js 18+ and retry.
    goto :end
)

echo [OK] All required executables detected.
echo.

:: Copy .env if it does not already exist
if not exist ".env" (
    if exist ".env.example" (
        copy ".env.example" ".env" >nul
        if errorlevel 1 (
            echo [ERROR] Failed to copy .env.example to .env.
            goto :error
        ) else (
            echo [.env] Created .env from .env.example.
        )
    ) else (
        echo [WARN] .env.example not found. Skipping .env creation.
    )
) else (
    echo [.env] Existing .env detected. Skipping copy.
)

echo.

:: Ensure SQLite database file exists (default setup)
if not exist "database" mkdir "database" >nul
if not exist "database\database.sqlite" (
    type nul > "database\database.sqlite"
    if errorlevel 1 (
        echo [ERROR] Could not create database\database.sqlite. Check permissions.
        goto :error
    ) else (
        echo [SQLite] Created database\database.sqlite
    )
) else (
    echo [SQLite] database\database.sqlite already exists.
)

echo.

echo [Step] Installing Composer dependencies...
call composer install --no-interaction --prefer-dist
if errorlevel 1 goto :error

echo.

echo [Step] Installing NPM dependencies...
call npm install
if errorlevel 1 goto :error

echo.

:: Generate application key if missing (empty or default placeholder)
set "APP_KEY_FOUND="
for /f "tokens=1,* delims==" %%A in ('findstr /b "APP_KEY=" ".env"') do (
    set "APP_KEY_FOUND=%%B"
)
if "!APP_KEY_FOUND!"=="" (
    echo [Step] Generating application key...
    call php artisan key:generate
    if errorlevel 1 goto :error
) else (
    echo [App Key] Existing APP_KEY detected.
)

echo.

echo [Step] Running migrations and seeders...
call php artisan migrate --seed
if errorlevel 1 goto :error

echo.

echo [Step] Clearing caches...
call php artisan optimize:clear
if errorlevel 1 goto :error

echo.

echo ==============================================
echo   %SCRIPT_NAME% - COMPLETE

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
