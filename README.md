# Ops Board — API

Backend Laravel 12 du projet **Ops Board**. Expose une API REST consommée par le front Next.js (`app.ops-board.dev.localhost`) et un panneau d'administration Filament.

## Stack

- **PHP 8.4** / **Laravel 12**
- **Filament 5** — back-office admin
- **Sanctum 4** — authentification SPA (cookies de session) pour le front Next.js
- **Pest 3** — tests
- **Scribe** — documentation API auto-générée
- **PostgreSQL 18** — base de données
- **Docker Compose + Traefik** — environnement local

## Architecture

- `app/Http/Controllers/Api/` — controllers REST (single-action invokables)
- `app/Http/Requests/` — Form Requests + `bodyParameters()` pour Scribe
- `app/Http/Resources/` — API Resources
- `app/Filament/` — ressources Filament pour l'admin
- `routes/api.php` — routes API montées sur `api/*`
- `bootstrap/app.php` — middleware (`statefulApi()` activé pour Sanctum SPA)

L'auth client utilise le pattern **Sanctum SPA** : sessions via cookies partagés sur le domaine parent `.ops-board.dev.localhost`. Le front et l'API doivent vivre sur des sous-domaines de cette racine pour que le cookie de session soit partagé.

## Démarrage

Le projet tourne dans Docker Compose, derrière un reverse-proxy Traefik externe.

### Prérequis

- Docker + Docker Compose
- Un network Docker `proxy` partagé avec Traefik (créé une fois : `docker network create proxy`)
- Traefik configuré pour servir `*.dev.localhost` en HTTPS (certificats auto-signés acceptés)

### Installation

```bash
cp .env.example .env
docker compose up -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

L'API est ensuite disponible sur **https://api.ops-board.dev.localhost**.

### Aliases shell utiles

```bash
alias dce='docker compose exec'
alias artisan='docker compose exec app php artisan'
alias composerapp='docker compose exec app composer'
```

## Authentification

Auth Sanctum SPA pour `app.ops-board.dev.localhost` :

| Variable | Valeur |
|---|---|
| `SESSION_DOMAIN` | `.ops-board.dev.localhost` |
| `SESSION_SAME_SITE` | `lax` |
| `SESSION_SECURE_COOKIE` | `true` |
| `SANCTUM_STATEFUL_DOMAINS` | `app.ops-board.dev.localhost` |
| `CORS_ALLOWED_ORIGINS` | `https://app.ops-board.dev.localhost` |

Côté front, avant toute requête mutante :

1. `GET https://api.ops-board.dev.localhost/sanctum/csrf-cookie` (avec `credentials: 'include'`)
2. Envoyer les requêtes suivantes avec `credentials: 'include'` et le header `X-XSRF-TOKEN` (axios le fait automatiquement)

### Endpoints d'auth

| Méthode | URI | Description |
|---|---|---|
| `POST` | `/api/register` | Crée un customer et ouvre une session |
| `POST` | `/api/login` | Authentifie un customer existant |
| `GET` | `/api/me` | Retourne le customer connecté |
| `POST` | `/api/logout` | Termine la session |

## Documentation API

Générée par Scribe :

```bash
artisan scribe:generate
```

Disponible sur **https://api.ops-board.dev.localhost/docs**.

## Admin Filament

Panneau admin sur **https://api.ops-board.dev.localhost/admin**. Le seeder `AdminUserSeeder` provisionne un compte admin par défaut (voir `database/seeders/AdminUserSeeder.php`).

## Tests

```bash
artisan test           # tous les tests
artisan test --compact # sortie condensée
artisan test --filter=Auth
```

Les tests utilisent Pest 3 + `RefreshDatabase`. La base de test est configurée dans `phpunit.xml`.

## Qualité de code

```bash
docker compose exec app vendor/bin/pint --dirty   # formatage
```

Pint est configuré au preset Laravel.

## Structure des fichiers clés

```
api/
├── app/
│   ├── Filament/                 # Ressources admin
│   ├── Http/
│   │   ├── Controllers/Api/      # Controllers REST
│   │   ├── Requests/             # Form Requests
│   │   └── Resources/            # API Resources
│   └── Models/
├── bootstrap/app.php             # Middleware + routing
├── config/
│   ├── cors.php
│   └── sanctum.php
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── routes/
│   ├── api.php
│   └── web.php
└── tests/
    ├── Feature/
    └── Unit/
```
