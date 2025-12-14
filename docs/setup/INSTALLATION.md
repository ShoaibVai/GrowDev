# Installation & Setup Guide

This guide provides detailed instructions for setting up GrowDev on your system.

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

### Required Software

- **PHP 8.2 or higher**
  - Extensions: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath
  - Check: `php -v` and `php -m`
  
- **Composer** (Latest version)
  - Package manager for PHP
  - Download: https://getcomposer.org/download/
  - Check: `composer --version`

- **Node.js 18+ and NPM**
  - JavaScript runtime and package manager
  - Download: https://nodejs.org/
  - Check: `node -v` and `npm -v`

- **MySQL 8.0+ or MariaDB 10.3+**
  - Relational database
  - Check: `mysql --version`

- **Git**
  - Version control
  - Download: https://git-scm.com/
  - Check: `git --version`

### Optional Software

- **Redis** (for queue and cache)
- **Mailpit** (for local email testing)

---

## 🚀 Quick Start

### 1. Clone the Repository

```bash
git clone <repository-url> growdev
cd growdev
```

### 2. Install PHP Dependencies

```bash
composer install
```

If you encounter memory issues:
```bash
php -d memory_limit=-1 /path/to/composer install
```

### 3. Install JavaScript Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the example environment file:

```bash
# Windows
copy .env.example .env

# Linux/Mac
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 5. Configure Database

Edit `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=growdev
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database:

```sql
CREATE DATABASE growdev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Run Migrations and Seeders

```bash
php artisan migrate:fresh --seed
```

This will:
- Create all database tables
- Seed with sample data
- Create demo users and projects

### 7. Build Frontend Assets

```bash
# Development build
npm run dev

# Production build
npm run build
```

### 8. Start Development Server

```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

---

## 🔧 Detailed Configuration

### Mail Configuration

#### Local Development (Mailpit)

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

Install Mailpit:
```bash
# Windows (via Scoop)
scoop install mailpit

# Mac (via Homebrew)
brew install mailpit

# Run
mailpit
```

Access Mailpit UI: `http://localhost:8025`

#### Production (Resend)

```env
MAIL_MAILER=resend
RESEND_KEY=your_resend_api_key
```

#### Production (Postmark)

```env
MAIL_MAILER=postmark
POSTMARK_TOKEN=your_postmark_token
```

### Queue Configuration

For background jobs (notifications, etc.):

#### Database Queue (Development)

```env
QUEUE_CONNECTION=database
```

Run the queue worker:
```bash
php artisan queue:work
```

#### Redis Queue (Production)

```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### AI Configuration

#### Using Google Gemini API

Get your API key from [Google AI Studio](https://aistudio.google.com/).

```env
GEMINI_API_KEY=your_gemini_api_key
GEMINI_PROJECT=your_project_id
GEMINI_PROJECT_NAME=projects/your_project_number
GEMINI_PROJECT_NUMBER=your_project_number
GEMINI_MODEL=gemini-flash-latest
```

### Storage Configuration

Link storage for file uploads:

```bash
php artisan storage:link
```

Set proper permissions:

```bash
# Linux/Mac
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Ensure web server user owns files
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

---

## 🖥️ Platform-Specific Setup

### Windows

#### Using the Setup Script

```bash
setup.bat
```

This automated script will:
1. Check for required software
2. Install Composer dependencies
3. Install NPM dependencies
4. Copy .env file
5. Generate app key
6. Run migrations and seeders
7. Build assets

#### Manual Setup

1. Install PHP via XAMPP or Windows PHP installer
2. Install Composer from getcomposer.org
3. Install Node.js from nodejs.org
4. Install MySQL via XAMPP or standalone
5. Follow the Quick Start guide above

### Linux (Ubuntu/Debian)

#### Using the Setup Script

```bash
chmod +x setup.sh
./setup.sh
```

#### Manual Setup

```bash
# Update package list
sudo apt update

# Install PHP and extensions
sudo apt install php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs

# Install MySQL
sudo apt install mysql-server

# Follow Quick Start guide
```

### macOS

#### Using Homebrew

```bash
# Install Homebrew (if not installed)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install PHP
brew install php@8.2

# Install Composer
brew install composer

# Install Node.js
brew install node@18

# Install MySQL
brew install mysql
brew services start mysql

# Follow Quick Start guide
```

---

## 🧪 Verification

### Test the Installation

```bash
# Run tests
php artisan test

# Check system status
php artisan about
```

### Access the Application

1. Navigate to `http://localhost:8000`
2. Login with demo credentials:
   - Email: `admin@growdev.com`
   - Password: `password`

### Verify Features

- ✅ Dashboard loads
- ✅ Can create a project
- ✅ Can add team members
- ✅ AI task generation works
- ✅ Notifications sent

---

## 🔍 Troubleshooting

### Common Issues

#### Database Connection Failed

```bash
# Check database is running
mysql -u root -p

# Verify credentials in .env
# Try connecting manually with credentials
```

#### Permission Denied Errors

```bash
# Linux/Mac
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

#### Composer Install Fails

```bash
# Increase memory limit
php -d memory_limit=-1 /path/to/composer install

# Clear cache
composer clear-cache
```

#### NPM Install Fails

```bash
# Clear NPM cache
npm cache clean --force

# Delete node_modules and reinstall
rm -rf node_modules package-lock.json
npm install
```

#### Assets Not Loading

```bash
# Rebuild assets
npm run build

# Clear Laravel cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Getting Help

- Check the [main README](../../README.md)
- Review [documentation](../)
- Open an issue on GitHub
- Contact: support@growdev.com

---

## 📚 Next Steps

After successful installation:

1. Read the [main README](../../README.md) for feature overview
2. Check [AI Integration Guide](../features/GEMINI_INTEGRATION.md)
3. Explore demo projects and tasks
4. Configure production settings
5. Set up automated backups
6. Configure monitoring

---

## 🔄 Updating

To update to the latest version:

```bash
# Pull latest changes
git pull origin main

# Update dependencies
composer install
npm install

# Run migrations
php artisan migrate

# Rebuild assets
npm run build

# Clear cache
php artisan config:clear
php artisan cache:clear
```

---

Last updated: 2025-12-15
