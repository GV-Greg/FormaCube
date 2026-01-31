#!/bin/bash

# Créer les répertoires de logs si nécessaire
echo "[Entrypoint] Creating log directories..."
mkdir -p /var/log/php /var/log/supervisor /var/log/nginx
chown -R www-data:www-data /var/log/php

# S'assurer que les permissions sont correctes
echo "[Entrypoint] Setting permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true

# Installer les dépendances si vendor n'existe pas
if [ ! -d "/var/www/vendor" ] || [ ! -f "/var/www/vendor/autoload.php" ]; then
    echo "[Entrypoint] Installing Composer dependencies..."
    cd /var/www && composer install --no-interaction || echo "[Entrypoint] Composer install failed, continuing..."
fi

# Installer les dépendances Node si node_modules n'existe pas
if [ ! -d "/var/www/node_modules" ]; then
    echo "[Entrypoint] Installing NPM dependencies..."
    cd /var/www && npm install || echo "[Entrypoint] NPM install failed, continuing..."
fi

# Attendre que la base de données soit prête
echo "[Entrypoint] Waiting for database connection..."
max_attempts=30
attempt=0
while ! php -r "new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" 2>/dev/null; do
    attempt=$((attempt + 1))
    if [ $attempt -ge $max_attempts ]; then
        echo "[Entrypoint] Database connection failed after $max_attempts attempts, continuing anyway..."
        break
    fi
    echo "[Entrypoint] Attempt $attempt/$max_attempts - Waiting for database..."
    sleep 2
done
echo "[Entrypoint] Database is ready!"

# Générer la clé d'application si nécessaire
if [ -f "/var/www/vendor/autoload.php" ]; then
    echo "[Entrypoint] Running Laravel setup..."
    cd /var/www
    php artisan key:generate --force 2>/dev/null || echo "[Entrypoint] Key generation skipped"
    php artisan migrate --force 2>/dev/null || echo "[Entrypoint] Migration skipped"
    php artisan storage:link 2>/dev/null || echo "[Entrypoint] Storage link skipped"
else
    echo "[Entrypoint] Vendor not installed, skipping Laravel setup"
fi

echo "[Entrypoint] Starting supervisor..."
echo "[Entrypoint] Application ready for development!"

# Exécuter la commande passée (supervisor)
exec "$@"
