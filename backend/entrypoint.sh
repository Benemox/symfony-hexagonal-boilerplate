#!/bin/sh
set -e

echo "🔧 Adjusting permissions for Symfony..."

echo "📦 Checking Composer dependencies..."
if [ ! -d "vendor" ]; then
    echo "📂 Ensuring vendor directory exists and is writable..."
    mkdir -p /var/www/html/vendor
    chown -R www-data:www-data /var/www/html/vendor
    chmod -R 775 /var/www/html/vendor
    echo "📦 vendor/ not found, running composer install..."
    composer install --no-interaction --optimize-autoloader
else
    echo "📦 vendor/ found, skipping composer install."
fi

echo "🔹 Ensuring log directories exist..."
mkdir -p /var/www/html/var/log

if [ "$(id -u)" = "0" ]; then
    echo "🔹 Setting ownership for cache and logs..."
    chown -R www-data:www-data /var/www/html/var /var/www/html/config || true
    chmod -R 775 /var/www/html/var /var/www/html/config
fi

# 👇 Esta es la clave: crea la migración inicial si no hay ninguna
if [ -z "$(ls -A /var/www/html/migrations/*.php 2>/dev/null)" ]; then
    echo "🛠 No migrations found, creating the initial migration..."
    php bin/console doctrine:migrations:diff || true
fi

echo "⚙️ Running Doctrine migrations..."
php bin/console doctrine:migrations:migrate --no-interaction || true

echo "🔍 Current directory permissions:"
ls -la /var/www/html/var
ls -la /var/www/html/public
ls -la /var/www/html/config

echo "🚀 Starting Apache..."
exec apache2-foreground
