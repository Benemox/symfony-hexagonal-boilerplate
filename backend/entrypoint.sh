#!/bin/sh
set -e

echo "🐘 Esperando a que MySQL esté disponible en database:3306..."
until nc -z database 3306; do
  echo "⏳ MySQL aún no está listo. Esperando..."
  sleep 1
done
echo "✅ MySQL está listo."

echo "🔧 Ajustando permisos para Symfony..."

echo "📦 Verificando dependencias con Composer..."
if [ ! -d "vendor" ]; then
    echo "📂 Asegurando que vendor/ existe y es escribible..."
    mkdir -p /var/www/html/vendor
    chown -R www-data:www-data /var/www/html/vendor
    chmod -R 775 /var/www/html/vendor
    echo "📦 vendor/ no encontrado, ejecutando composer install..."
    composer install --no-interaction --optimize-autoloader
else
    echo "📦 vendor/ encontrado, composer install omitido."
fi

echo "🔹 Asegurando carpetas de logs..."
mkdir -p /var/www/html/var/log

if [ "$(id -u)" = "0" ]; then
    echo "🔹 Ajustando permisos de var/ y config/..."
    chown -R www-data:www-data /var/www/html/var /var/www/html/config || true
    chmod -R 775 /var/www/html/var /var/www/html/config
fi

if [ -z "$(ls -A /var/www/html/migrations/*.php 2>/dev/null)" ]; then
    echo "🛠 No se encontraron migraciones, creando una inicial..."
    php bin/console doctrine:migrations:diff || true
fi

echo "⚙️ Ejecutando migraciones Doctrine..."
php bin/console doctrine:migrations:migrate --no-interaction || true

echo "🔍 Permisos actuales:"
ls -la /var/www/html/var
ls -la /var/www/html/public
ls -la /var/www/html/config

echo "🚀 Iniciando Apache..."
exec apache2-foreground
