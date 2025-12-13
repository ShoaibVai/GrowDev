#!/bin/bash

echo "Setting up GrowDev Project..."

echo "Installing dependencies..."
composer install
npm install

echo "Setting up environment..."
if [ ! -f .env ]; then
    cp .env.example .env
fi
php artisan key:generate

echo "Migrating and Seeding database..."
php artisan migrate:fresh --seed

echo "Building assets..."
npm run build

echo "Setup Complete!"
echo "Admin Credentials: admin@growdev.com / password"
echo "Run 'php artisan serve' to start the server."
