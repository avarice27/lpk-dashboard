#!/bin/bash

echo "🚀 Memulai deployment LPK Dashboard..."

# Update dependencies
echo "📦 Update Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Build assets
echo "🎨 Build production assets..."
npm install
npm run build

# Clear caches
echo "🧹 Clear Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimize for production
echo "⚡ Optimize Laravel for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "🔐 Set proper permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Run migrations
echo "🗄️ Run database migrations..."
php artisan migrate --force

echo "✅ Deployment selesai!"
echo "🌐 Aplikasi siap diakses di production!"
