#!/bin/bash
set -e

# Créer les répertoires de logs si nécessaire
mkdir -p /var/log/php /var/log/supervisor /var/log/nginx
chown -R www-data:www-data /var/log/php

# S'assurer que les permissions sont correctes
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Attendre que la base de données soit prête
echo "Waiting for database connection..."
while ! php -r "new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" 2>/dev/null; do
    sleep 2
done
echo "Database is ready!"

# Exécuter les migrations
php artisan migrate --force

# Vider et reconstruire les caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Créer le lien symbolique pour le storage
php artisan storage:link || true

echo "Application ready!"

exec "$@"
