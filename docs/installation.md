# Installation Guide

This guide will help you set up the GrowDev project management platform on your local development environment.

## Prerequisites

Before you begin, ensure you have the following installed:

- **PHP 8.3+** with extensions:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML
  - cURL
- **Composer 2.7+**
- **Node.js 20+**
- **Git**

## Quick Setup (Recommended)

### Windows
Run the setup script in PowerShell or Command Prompt:
```cmd
setup.bat
```

### macOS/Linux
Run the setup script in Terminal:
```bash
chmod +x setup.sh
./setup.sh
```

## Manual Setup

If you prefer to set up manually or the script doesn't work:

### 1. Clone the Repository
```bash
git clone https://github.com/shoaibomar/GrowDev.git
cd GrowDev
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Environment Variables

Edit your `.env` file and update the following:

```env
# Application
APP_NAME=GrowDev
APP_URL=http://localhost:8000

# Supabase Configuration
SUPABASE_URL=https://bwrxvijpmhnuevdrtxcy.supabase.co
SUPABASE_ANON_KEY=your_anon_key_here
SUPABASE_SERVICE_ROLE_KEY=your_service_role_key_here

# Database (Supabase PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=db.bwrxvijpmhnuevdrtxcy.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your_database_password
```

### 5. Database Setup

1. **Access Supabase Dashboard**
   - Go to [https://app.supabase.com](https://app.supabase.com)
   - Navigate to your project dashboard

2. **Run Database Schema**
   - Go to SQL Editor
   - Copy and paste the contents of `database/migrations/supabase_schema.sql`
   - Execute the script

3. **Verify Setup**
   - Check that all tables are created
   - Verify Row Level Security policies are in place

### 6. Build Assets
```bash
# Build for development
npm run dev

# Or build for production
npm run build
```

### 7. Create Storage Link
```bash
php artisan storage:link
```

## Running the Application

### Development Server
```bash
# Start Laravel server
php artisan serve

# In another terminal, start asset watcher
npm run dev
```

Your application will be available at: [http://localhost:8000](http://localhost:8000)

## Verification

1. **Check PHP Configuration**
   ```bash
   php --version
   php -m | grep -E "pdo|mbstring|openssl|tokenizer|xml|curl"
   ```

2. **Verify Composer**
   ```bash
   composer --version
   composer diagnose
   ```

3. **Check Node.js/NPM**
   ```bash
   node --version
   npm --version
   ```

4. **Test Database Connection**
   ```bash
   php artisan tinker
   # In tinker console:
   DB::connection()->getPdo();
   ```

## Troubleshooting

### Common Issues

1. **Composer Install Fails**
   - Ensure all PHP extensions are installed
   - Check PHP memory limit: `php -d memory_limit=2G composer install`

2. **NPM Install Fails**
   - Clear npm cache: `npm cache clean --force`
   - Delete `node_modules` and `package-lock.json`, then `npm install`

3. **Database Connection Issues**
   - Verify Supabase credentials in `.env`
   - Check if Supabase project is active
   - Ensure database password is correct

4. **Asset Build Fails**
   - Check Node.js version compatibility
   - Clear Vite cache: `rm -rf node_modules/.vite`

5. **Permission Issues (Linux/macOS)**
   ```bash
   sudo chown -R $USER:$USER storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```

### Windows-Specific Issues

1. **Symlink Creation Fails**
   - Run Command Prompt as Administrator
   - Or use: `php artisan storage:link --relative`

2. **Long Path Issues**
   - Enable long paths in Windows
   - Or move project to shorter path

### Getting Help

If you encounter issues:

1. Check the [GitHub Issues](https://github.com/shoaibomar/GrowDev/issues)
2. Review Laravel documentation: [https://laravel.com/docs](https://laravel.com/docs)
3. Check Supabase documentation: [https://supabase.com/docs](https://supabase.com/docs)

## Next Steps

After successful installation:

1. [Read the API Documentation](api.md)
2. [Review Contributing Guidelines](contributing.md)
3. [Explore the User Guide](user-guide.md)

## Production Deployment

For production deployment instructions, see our [Deployment Guide](deployment.md).