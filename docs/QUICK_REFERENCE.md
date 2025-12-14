# Quick Reference Guide

A quick command reference for common GrowDev development tasks.

## 🚀 Getting Started

```bash
# Clone and setup
git clone <repo-url> growdev && cd growdev
composer install && npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

## 📦 Dependencies

```bash
# Install PHP packages
composer install
composer update

# Install JavaScript packages
npm install
npm update

# Add new package
composer require vendor/package
npm install package-name
```

## 🗄️ Database

```bash
# Create migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate
php artisan migrate:fresh
php artisan migrate:fresh --seed

# Rollback
php artisan migrate:rollback
php artisan migrate:rollback --step=1

# Seed database
php artisan db:seed
php artisan db:seed --class=SpecificSeeder
```

## 🎨 Frontend Assets

```bash
# Development (with hot reload)
npm run dev

# Production build
npm run build

# Watch for changes
npm run watch
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/TaskTest.php

# Run with coverage
php artisan test --coverage

# Run specific test method
php artisan test --filter test_user_can_create_task
```

## 🎯 Code Generation

```bash
# Make controller
php artisan make:controller NameController
php artisan make:controller NameController --resource
php artisan make:controller NameController --api

# Make model
php artisan make:model Name
php artisan make:model Name -mfcs  # with migration, factory, controller, seeder

# Make middleware
php artisan make:middleware NameMiddleware

# Make request
php artisan make:request StoreNameRequest

# Make policy
php artisan make:policy NamePolicy --model=Name

# Make notification
php artisan make:notification NameNotification

# Make event & listener
php artisan make:event NameEvent
php artisan make:listener NameListener --event=NameEvent

# Make command
php artisan make:command NameCommand

# Make seeder
php artisan make:seeder NameSeeder

# Make factory
php artisan make:factory NameFactory --model=Name

# Make service (custom)
mkdir -p app/Services && touch app/Services/NameService.php
```

## 🧹 Cache & Optimization

```bash
# Clear all cache
php artisan optimize:clear

# Clear specific cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
composer dump-autoload -o
```

## 📊 Queue Management

```bash
# Run queue worker
php artisan queue:work

# Run specific queue
php artisan queue:work --queue=default,emails

# Process one job
php artisan queue:work --once

# Restart workers
php artisan queue:restart

# Check failed jobs
php artisan queue:failed

# Retry failed job
php artisan queue:retry <job-id>
php artisan queue:retry all
```

## 🔐 Security

```bash
# Generate app key
php artisan key:generate

# Create storage link
php artisan storage:link

# Generate policy
php artisan make:policy ModelPolicy --model=Model
```

## 📝 Code Quality

```bash
# Format code (Laravel Pint)
./vendor/bin/pint

# Run static analysis
./vendor/bin/phpstan analyse

# Run PHP CS Fixer
./vendor/bin/php-cs-fixer fix
```

## 🔍 Debugging

```bash
# Tinker (REPL)
php artisan tinker

# Show routes
php artisan route:list
php artisan route:list --path=api

# Show application info
php artisan about

# Log tail
tail -f storage/logs/laravel.log
```

## 🔧 Maintenance

```bash
# Enter maintenance mode
php artisan down
php artisan down --secret="1630542a-246b-4b66-afa1-dd72a4c43515"

# Exit maintenance mode
php artisan up
```

## 📦 Custom Scripts

```bash
# Export database
php scripts/export-database.php

# List users
php scripts/list-users.php

# Check teams
php scripts/check-teams.php
php scripts/check-all-teams.php

# Check dashboard
php scripts/check-dashboard.php
```

## 🌐 Server Management

```bash
# Start development server
php artisan serve
php artisan serve --host=0.0.0.0 --port=8080

# Start queue worker (development)
php artisan queue:listen

# Start queue worker (production)
php artisan queue:work --daemon
```

## 📚 Documentation

```bash
# Generate API documentation (if configured)
php artisan l5-swagger:generate
```

## 🐳 Docker (if using)

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View logs
docker-compose logs -f

# Run artisan in container
docker-compose exec app php artisan migrate
```

## Git Workflow

```bash
# Create feature branch
git checkout -b feature/feature-name

# Commit changes
git add .
git commit -m "feat: add feature description"

# Push to remote
git push origin feature/feature-name

# Update from main
git fetch origin
git rebase origin/main
```

## 🚨 Troubleshooting

```bash
# Permission issues (Linux/Mac)
sudo chown -R $USER:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Composer memory issues
php -d memory_limit=-1 /path/to/composer install

# Clear everything and start fresh
php artisan optimize:clear
composer dump-autoload
npm run build
```

## 📖 Useful Artisan Commands

```bash
# List all commands
php artisan list

# Get help for command
php artisan help migrate

# Show environment
php artisan env

# Show config value
php artisan config:show database

# Run schedule manually (cron jobs)
php artisan schedule:run
php artisan schedule:list
```

## 🔥 Hot Tips

```bash
# Quick reset for development
php artisan migrate:fresh --seed && npm run build

# Watch logs in real-time
tail -f storage/logs/laravel.log | grep -i error

# Find TODO comments
grep -r "TODO" app/

# Count lines of code
find app -name "*.php" | xargs wc -l

# Check PHP version and extensions
php -v && php -m
```

---

💡 **Pro Tip**: Create aliases for frequently used commands in your `.bashrc` or `.zshrc`:

```bash
alias pa="php artisan"
alias pam="php artisan migrate"
alias pams="php artisan migrate:fresh --seed"
alias pat="php artisan test"
alias nrb="npm run build"
alias nrd="npm run dev"
```

---

Last updated: 2025-12-15
