#!/bin/bash

# GrowDev Project Setup Script for Linux/Mac
# This script sets up the GrowDev project on a fresh machine

set -e  # Exit on error

echo "=============================================="
echo "  GrowDev Project Setup"
echo "=============================================="
echo ""
echo "This script will set up the GrowDev project."
echo ""

# ==================================================================
# STEP 1: Check Prerequisites
# ==================================================================

echo "[Step 1] Checking prerequisites..."
echo ""

# Check PHP
if ! command -v php &> /dev/null; then
    echo "[ERROR] PHP not found."
    echo ""
    echo "Please install PHP 8.2+ first:"
    echo "  Ubuntu/Debian: sudo apt install php8.2 php8.2-cli php8.2-mbstring php8.2-xml php8.2-curl php8.2-sqlite3"
    echo "  MacOS: brew install php@8.2"
    echo ""
    exit 1
else
    echo "[OK] PHP found: $(php -v | head -n 1)"
fi

# Check Composer
if ! command -v composer &> /dev/null; then
    echo "[ERROR] Composer not found."
    echo ""
    echo "Please install Composer from: https://getcomposer.org/download/"
    echo "  Quick install:"
    echo "  curl -sS https://getcomposer.org/installer | php"
    echo "  sudo mv composer.phar /usr/local/bin/composer"
    echo ""
    exit 1
else
    echo "[OK] Composer found: $(composer -V | head -n 1)"
fi

# Check Node.js
if ! command -v node &> /dev/null; then
    echo "[ERROR] Node.js not found."
    echo ""
    echo "Please install Node.js 18+ from: https://nodejs.org/"
    echo "  Recommended: Node.js 24 LTS"
    echo ""
    echo "Ubuntu/Debian:"
    echo "  curl -fsSL https://deb.nodesource.com/setup_24.x | sudo -E bash -"
    echo "  sudo apt-get install -y nodejs"
    echo ""
    echo "MacOS:"
    echo "  brew install node@24"
    echo ""
    exit 1
else
    echo "[OK] Node.js found: $(node -v)"
fi

# Check npm
if ! command -v npm &> /dev/null; then
    echo "[ERROR] npm not found. This usually comes with Node.js."
    echo "Please reinstall Node.js."
    exit 1
else
    echo "[OK] npm found: $(npm -v)"
fi

echo ""
echo "[OK] All prerequisites satisfied!"
echo ""

# ==================================================================
# STEP 2: Environment Configuration
# ==================================================================

echo "[Step 2] Configuring environment..."
echo ""

if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        cp .env.example .env
        echo "[OK] Created .env from .env.example"
    else
        echo "[WARN] .env.example not found. Skipping .env creation."
    fi
else
    echo "[OK] .env already exists"
fi

echo ""

# ==================================================================
# STEP 3: Database Setup
# ==================================================================

echo "[Step 3] Setting up database..."
echo ""

# Create database directory if it doesn't exist
mkdir -p database

# Create SQLite database file if it doesn't exist
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    echo "[OK] Created database/database.sqlite"
else
    echo "[OK] database/database.sqlite already exists"
fi

echo ""

# ==================================================================
# STEP 4: Install Dependencies
# ==================================================================

echo "[Step 4] Installing dependencies..."
echo ""

echo "[Composer] Installing PHP packages..."
if ! composer install --no-interaction --prefer-dist; then
    echo "[WARN] composer install failed. Trying composer update..."
    composer update --no-interaction
fi

echo ""

echo "[npm] Installing JavaScript packages..."
npm install --loglevel=error

echo ""

# ==================================================================
# STEP 5: Build Assets
# ==================================================================

echo "[Step 5] Building frontend assets..."
echo ""

npm run build

echo ""

# ==================================================================
# STEP 6: Laravel Setup
# ==================================================================

echo "[Step 6] Setting up Laravel..."
echo ""

# Generate application key if not present
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "[Laravel] Generating application key..."
    php artisan key:generate --ansi
else
    echo "[OK] APP_KEY already set"
fi

echo ""

# ==================================================================
# STEP 7: Database Migration & Seeding
# ==================================================================

echo "[Step 7] Running database migrations and seeders..."
echo ""

php artisan migrate:fresh --seed --force

echo ""

# ==================================================================
# SETUP COMPLETE
# ==================================================================

echo "=============================================="
echo "  SETUP COMPLETE!"
echo "=============================================="
echo ""
echo "[SUCCESS] GrowDev is ready to use!"
echo ""
echo "Default Admin Account:"
echo "  Email: admin@growdev.com"
echo "  Password: password"
echo ""
echo "IMPORTANT - Configure your .env file:"
echo "  1. Set GEMINI_API_KEY for AI task generation"
echo "  2. Configure mail settings for notifications"
echo "  3. Adjust database settings if needed"
echo ""
echo "To start development:"
echo "  composer dev    (runs everything: server + queue + logs + vite)"
echo ""
echo "Or run individually:"
echo "  php artisan serve             - Start web server"
echo "  php artisan queue:work        - Start queue worker (required!)"
echo "  npm run dev                   - Start Vite dev server"
echo ""
