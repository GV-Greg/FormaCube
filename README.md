<div align="center">
    <img src="https://sd-1.archive-host.com/membres/up/9115037260420014/WebDevelopper/readme-github/Logo_FormaCube-github.png" alt="logo project">
</div>

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel" alt="Laravel">
    <img src="https://img.shields.io/badge/Vue.js-3.5-4FC08D?style=flat-square&logo=vue.js" alt="Vue.js">
    <img src="https://img.shields.io/badge/Vuetify-3.8-1867C0?style=flat-square&logo=vuetify" alt="Vuetify">
    <img src="https://img.shields.io/badge/Vite-6.x-646CFF?style=flat-square&logo=vite" alt="Vite">
    <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php" alt="PHP">
    <img src="https://img.shields.io/badge/Docker-Ready-2496ED?style=flat-square&logo=docker" alt="Docker">
</p>

## 📋 About FormaCube

FormaCube is a web-based ERP application designed for training organizations. It provides comprehensive management of:

- 🎓 **Formations** - Training sessions management
- 📅 **Recrutements** - Recruitment sessions scheduling
- 👥 **Inscrits** - Trainees/participants management
- 🏷️ **Tags** - Categorization system
- 📍 **Salles** - Training rooms management
- 👤 **Prospects** - Potential trainees tracking
- 👨‍💼 **Utilisateurs** - User management with roles

---

## 🛠️ Tech Stack

| Category | Technology |
|----------|------------|
| **Backend** | Laravel 11.48, PHP 8.3 |
| **Frontend** | Vue.js 3.5, Vuetify 3.8 |
| **Build Tool** | Vite 6.4 |
| **State Management** | Pinia 2.3 |
| **Authentication** | JWT (php-open-source-saver/jwt-auth) |
| **Database** | MySQL 8.0 |
| **Cache/Session** | Redis 7 |
| **Mail (Dev)** | Mailpit |
| **Containerization** | Docker + Docker Compose |

---

## 🚀 Quick Start

### Prerequisites

- Docker & Docker Compose
- Git

### Installation

```bash
# Clone the repository
git clone https://github.com/your-repo/formacube.git
cd formacube

# Copy environment file
cp .env.example .env

# Start Docker containers (development)
docker compose -f docker-compose.dev.yml up -d --build

# Install PHP dependencies
docker exec formacube_app_dev composer install

# Generate application key
docker exec formacube_app_dev php artisan key:generate

# Generate JWT secret
docker exec formacube_app_dev php artisan jwt:secret

# Run migrations
docker exec formacube_app_dev php artisan migrate

# Create storage link
docker exec formacube_app_dev php artisan storage:link

# Install Node dependencies & build (handled by container)
# Vite dev server starts automatically
```

### Access the Application

| Service | URL |
|---------|-----|
| **Application** | http://localhost:8080 |
| **Vite Dev Server** | http://localhost:5173 |
| **Mailpit** | http://localhost:8025 |

---

## 🐳 Docker Architecture

The development environment uses **supervisord** to manage multiple services in a single container:

```
formacube_app_dev
├── nginx (port 8080)
├── php-fpm (PHP 8.3)
└── vite (HMR on port 5173)

formacube_db_dev
└── MySQL 8.0 (port 3306)

formacube_redis_dev
└── Redis 7 (port 6379)

formacube_mailpit_dev
├── SMTP (port 1025)
└── Web UI (port 8025)
```

### Useful Commands

```bash
# View logs
docker logs formacube_app_dev -f

# Restart Vite
docker exec formacube_app_dev supervisorctl restart vite

# Access container shell
docker exec -it formacube_app_dev bash

# Run artisan commands
docker exec formacube_app_dev php artisan <command>

# Clear all caches
docker exec formacube_app_dev php artisan optimize:clear
```

---

## 📁 Project Structure

```
formacube/
├── app/
│   ├── Http/
│   │   ├── Controllers/    # API Controllers
│   │   ├── Middleware/     # JWT & CORS middleware
│   │   └── Resources/      # API Resources
│   ├── Model/              # Eloquent Models
│   └── Providers/          # Service Providers
├── resources/
│   ├── js/
│   │   ├── components/     # Vue Components
│   │   │   ├── pages/      # Page components
│   │   │   └── elements/   # Reusable components
│   │   ├── store/          # Pinia stores
│   │   ├── router/         # Vue Router config
│   │   └── app.js          # Vue app entry point
│   ├── plugins/
│   │   └── vuetify.js      # Vuetify configuration
│   └── sass/               # SCSS styles
├── routes/
│   ├── api.php             # API routes (JWT protected)
│   └── web.php             # Web routes (SPA entry)
├── docker/                 # Docker configuration files
├── vite.config.js          # Vite configuration
└── docker-compose.dev.yml  # Development Docker setup
```

---

## 🔄 Migration History (v5 → v6)

This project underwent a major migration in January 2026:

### Backend Updates
- ✅ Laravel 8 → **Laravel 11.48**
- ✅ PHP 7.4 → **PHP 8.3**
- ✅ tymon/jwt-auth → **php-open-source-saver/jwt-auth v2.8**

### Frontend Updates
- ✅ Vue 2 → **Vue 3.5** (Composition API ready)
- ✅ Vuetify 2 → **Vuetify 3.8**
- ✅ Vuex → **Pinia 2.3**
- ✅ Laravel Mix → **Vite 6.4**
- ✅ Font Awesome → **MDI Icons**

### Component Migration Highlights

| Component | Changes |
|-----------|---------|
| `v-simple-table` | → `v-table` |
| `v-data-table` | → Updated props/slots |
| `v-text-field` | → `variant="outlined"` |
| `v-select` | → New item props syntax |
| `v-btn` | → `variant`, `size` props |
| `v-alert` | → `type`, `variant` props |
| Filters | → Methods (Vue 3) |
| `$store` | → Pinia stores |
| Bootstrap classes | → Vuetify grid system |

---

## 🧪 Testing

```bash
# Run PHP tests
docker exec formacube_app_dev php artisan test

# Run with coverage
docker exec formacube_app_dev php artisan test --coverage
```

---

## 📝 Environment Variables

Key environment variables (see `.env.example` for full list):

```env
# Application
APP_URL=http://localhost:8080

# Database
DB_HOST=db
DB_DATABASE=formacube

# Redis
REDIS_HOST=redis

# Mail (Mailpit for dev)
MAIL_HOST=mailpit
MAIL_PORT=1025

# JWT
JWT_TTL=60
JWT_REFRESH_TTL=20160
```

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📄 License

This project is proprietary software developed for training organization management.

---

<div align="center">
    <strong>Version 6.0</strong> • Copyright © 2019-2026 <a href="http://www.creacube.be/">CreaCube</a>
</div>
