#!/bin/bash

# GrowDev Project Setup Script
# This script helps set up the GrowDev Laravel + Vue.js project

echo "🚀 GrowDev Project Setup"
echo "========================="

# Check if we're in Windows (Git Bash, WSL, etc.)
if [[ "$OSTYPE" == "msys" || "$OSTYPE" == "win32" ]]; then
    echo "🪟 Windows environment detected"
    COMPOSER_CMD="composer"
    NPM_CMD="npm"
    PHP_CMD="php"
else
    echo "🐧 Unix-like environment detected"
    COMPOSER_CMD="composer"
    NPM_CMD="npm"
    PHP_CMD="php"
fi

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Check prerequisites
echo ""
echo "📋 Checking prerequisites..."

if ! command_exists php; then
    echo "❌ PHP is not installed or not in PATH"
    echo "   Please install PHP 8.3 or higher"
    exit 1
else
    echo "✅ PHP found: $(php --version | head -n1)"
fi

if ! command_exists composer; then
    echo "⚠️ Composer not found in PATH, checking for local installation..."
    if [ -f "composer.phar" ]; then
        echo "✅ Local Composer found"
        COMPOSER_CMD="php composer.phar"
    elif [ -f "composer" ]; then
        echo "✅ Local Composer found"
        COMPOSER_CMD="php composer"
    else
        echo "📦 Installing Composer locally..."
        curl -sS https://getcomposer.org/installer | php -- --filename=composer
        if [ -f "composer" ]; then
            echo "✅ Composer installed locally"
            COMPOSER_CMD="php composer"
        else
            echo "❌ Failed to install Composer"
            echo "   Please install Composer manually from https://getcomposer.org/"
            exit 1
        fi
    fi
else
    echo "✅ Composer found: $(composer --version)"
    COMPOSER_CMD="composer"
fi

if ! command_exists node; then
    echo "❌ Node.js is not installed or not in PATH"
    echo "   Please install Node.js 20 or higher"
    exit 1
else
    echo "✅ Node.js found: $(node --version)"
fi

if ! command_exists npm; then
    echo "❌ NPM is not installed or not in PATH"
    exit 1
else
    echo "✅ NPM found: $(npm --version)"
fi

# Check if .env file exists
echo ""
echo "🔧 Setting up environment..."

if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo "✅ Created .env file from .env.example"
    else
        echo "❌ .env.example file not found"
        exit 1
    fi
else
    echo "ℹ️ .env file already exists"
fi

# Install Composer dependencies
echo ""
echo "📦 Installing PHP dependencies..."
$COMPOSER_CMD install --no-interaction --optimize-autoloader

if [ $? -ne 0 ]; then
    echo "❌ Failed to install Composer dependencies"
    exit 1
fi

echo "✅ PHP dependencies installed"

# Install NPM dependencies
echo ""
echo "📦 Installing JavaScript dependencies..."
$NPM_CMD install

if [ $? -ne 0 ]; then
    echo "❌ Failed to install NPM dependencies"
    exit 1
fi

echo "✅ JavaScript dependencies installed"

# Generate Laravel application key
echo ""
echo "🔑 Generating application key..."
$PHP_CMD artisan key:generate --ansi

if [ $? -ne 0 ]; then
    echo "❌ Failed to generate application key"
    exit 1
fi

echo "✅ Application key generated"

# Create storage symbolic link
echo ""
echo "🔗 Creating storage link..."
$PHP_CMD artisan storage:link

# Set up Supabase configuration
echo ""
echo "🗄️ Supabase Configuration"
echo "========================="
echo ""
echo "Please update your .env file with your Supabase credentials:"
echo ""
echo "SUPABASE_URL=https://bwrxvijpmhnuevdrtxcy.supabase.co"
echo "SUPABASE_ANON_KEY=your_anon_key_here"
echo "SUPABASE_SERVICE_ROLE_KEY=your_service_role_key_here"
echo ""
echo "Database Schema Setup:"
echo "1. Go to your Supabase dashboard"
echo "2. Navigate to SQL Editor"
echo "3. Run the SQL script from: database/migrations/supabase_schema.sql"
echo ""

# Build assets
echo ""
echo "🎨 Building frontend assets..."
$NPM_CMD run build

if [ $? -ne 0 ]; then
    echo "❌ Failed to build assets"
    exit 1
fi

echo "✅ Assets built successfully"

# Final instructions
echo ""
echo "🎉 Setup Complete!"
echo "=================="
echo ""
echo "Next steps:"
echo "1. Update your .env file with Supabase credentials"
echo "2. Run the Supabase schema SQL in your database"
echo "3. Start the development server:"
echo "   php artisan serve"
echo ""
echo "4. In another terminal, start the asset watcher:"
echo "   npm run dev"
echo ""
echo "5. Visit http://localhost:8000 to see your application"
echo ""
echo "For more information, see the README.md file"
echo ""

# Check if we should start the server
read -p "Would you like to start the development server now? (y/n): " -n 1 -r
echo ""
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🚀 Starting development server..."
    $PHP_CMD artisan serve
fi