# FormaCube - Configuration Docker

## Prérequis

- Docker Desktop (Windows/Mac) ou Docker Engine (Linux)
- Docker Compose v2+
- Base de données MySQL externe (locale ou distante)

## Démarrage rapide

### Développement (avec hot reload)

```bash
# Construire et démarrer les containers en mode développement
docker compose -f docker-compose.dev.yml up --build

# Ou en arrière-plan
docker compose -f docker-compose.dev.yml up -d --build
```

### Production

```bash
# Construire et démarrer les containers en mode production
docker compose up --build -d
```

## Services disponibles

| Service     | URL                       | Description                    |
|-------------|---------------------------|--------------------------------|
| Application | http://localhost:8080     | Application Laravel + Vue      |
| Vite        | http://localhost:5173     | Serveur de développement Vite  |
| Mailpit     | http://localhost:8025     | Interface de test des emails   |
| Redis       | localhost:6379            | Cache et sessions              |

## Variables d'environnement

Créez un fichier `.env` à la racine du projet (ou utilisez `.env.example`) :

```env
APP_NAME=FormaCube
APP_ENV=local
APP_DEBUG=true

# Base de données externe
DB_HOST=host.docker.internal  # Pour accéder à MySQL sur votre machine hôte
# DB_HOST=192.168.1.100       # Ou l'IP de votre serveur MySQL
DB_PORT=3306
DB_DATABASE=formacube
DB_USERNAME=formacube
DB_PASSWORD=secret

REDIS_HOST=redis
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
```

### Connexion à la base de données

- **MySQL sur la machine hôte** : Utilisez `DB_HOST=host.docker.internal`
- **MySQL sur un serveur distant** : Utilisez l'IP ou le hostname du serveur
- **MySQL en local (Windows)** : Assurez-vous que MySQL accepte les connexions depuis Docker

## Commandes utiles

### Arrêter les containers

```bash
# Développement
docker compose -f docker-compose.dev.yml down

# Production
docker compose down
```

### Accéder au container

```bash
# Exécuter des commandes artisan
docker compose exec app php artisan migrate

# Accéder au shell
docker compose exec app bash

# NPM commands
docker compose exec app npm run build
```

### Logs

```bash
# Voir tous les logs
docker compose logs -f

# Logs d'un service spécifique
docker compose logs -f app
```

### Reconstruire les containers

```bash
docker compose down
docker compose up --build -d
```

### Nettoyer complètement

```bash
# Supprimer les containers, volumes et images
docker compose down -v --rmi local
```

## Structure des fichiers Docker

```
├── Dockerfile              # Image de production
├── Dockerfile.dev          # Image de développement
├── docker-compose.yml      # Configuration production
├── docker-compose.dev.yml  # Configuration développement
├── .dockerignore           # Fichiers à ignorer
└── docker/
    ├── nginx/
    │   ├── nginx.conf      # Configuration principale Nginx
    │   ├── default.conf    # Virtual host production
    │   └── default.dev.conf # Virtual host développement
    ├── php/
    │   ├── php.ini         # Configuration PHP
    │   └── php-fpm.conf    # Configuration PHP-FPM
    ├── supervisor/
    │   ├── supervisord.conf     # Supervisor production
    │   └── supervisord.dev.conf # Supervisor développement
    ├── entrypoint.sh       # Script démarrage production
    └── entrypoint.dev.sh   # Script démarrage développement
```

## Dépannage

### Permission denied sur storage/

```bash
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
docker compose exec app chmod -R 775 storage bootstrap/cache
```

### Connexion à la base de données refusée

1. Vérifiez que votre serveur MySQL accepte les connexions distantes
2. Vérifiez les credentials dans le fichier `.env`
3. Pour MySQL local, assurez-vous que l'utilisateur peut se connecter depuis l'IP Docker

```sql
-- Sur votre serveur MySQL, autorisez les connexions Docker
CREATE USER 'formacube'@'%' IDENTIFIED BY 'secret';
GRANT ALL PRIVILEGES ON formacube.* TO 'formacube'@'%';
FLUSH PRIVILEGES;
```

### Vite ne fonctionne pas

Vérifiez que le port 5173 est bien exposé et que Vite est démarré :

```bash
docker compose exec app ps aux | grep vite
```
