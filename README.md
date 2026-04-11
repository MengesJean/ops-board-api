# Ops Board — API

> ⚠️ **Projet de démonstration — pas un produit en production.**
> Ce dépôt fait partie d'un projet vitrine construit pour mon portfolio personnel : **[www.mengesjean.fr](https://www.mengesjean.fr)**.
> Il n'est ni audité, ni durci pour un environnement de production. Il sert à illustrer une stack et des choix d'architecture côté back-end Laravel — rien de plus.

---

Backend Laravel 12 du projet **Ops Board**, une application de gestion de portefeuille projets / clients / tâches.
L'API REST est consommée par un front Next.js (`app.ops-board.dev.localhost`) et complétée par un back-office Filament pour l'administration interne.

## Sommaire

- [Stack technique](#stack-technique)
- [Fonctionnalités](#fonctionnalités)
- [Modèle métier](#modèle-métier)
- [Architecture](#architecture)
- [Démarrage](#démarrage)
- [Authentification Sanctum SPA](#authentification-sanctum-spa)
- [Endpoints API](#endpoints-api)
- [Admin Filament](#admin-filament)
- [Documentation API](#documentation-api-scribe)
- [Tests](#tests)
- [Qualité de code](#qualité-de-code)
- [Structure du projet](#structure-du-projet)

## Stack technique

| Catégorie | Outils |
|---|---|
| Langage / Framework | **PHP 8.4**, **Laravel 12** |
| Authentification | **Laravel Sanctum 4** (SPA cookie + bearer tokens) |
| Back-office | **Filament 5** |
| Base de données | **PostgreSQL 18** |
| Tests | **Pest 3** + RefreshDatabase |
| Documentation API | **Scribe** (HTML + OpenAPI 3.0.3 + Postman collection) |
| Formatage | **Laravel Pint** (preset Laravel) |
| Environnement local | **Docker Compose + Traefik** |

## Fonctionnalités

L'API couvre l'ensemble du cycle de pilotage projet, de l'authentification du customer jusqu'au dashboard d'agrégation.

### Authentification customer (Sanctum SPA)

- Inscription, connexion, déconnexion d'un `Customer` via cookies de session.
- Endpoint `/api/me` pour le rehydrate front.
- Compatible bearer token pour clients tiers / mobile.

### Gestion des clients

- CRUD complet d'un `Client` métier appartenant au customer authentifié.
- Recherche plein texte sur `name` / `company_name`, filtre par `status`.
- Pagination configurable.

### Gestion des projets

- CRUD complet d'un `Project` rattaché à un `Client`.
- Filtres : `client_id`, `status`, `priority`, `health`, recherche sur `name` / `reference`.
- Tri configurable sur `due_date` / `updated_at` / `created_at`.
- Statuts riches (`draft`, `planned`, `active`, `on_hold`, `completed`, `cancelled`), priorité, santé (`good` / `warning` / `critical`), budget, dates clés.

### Roadmap projet : milestones

- CRUD complet d'une `ProjectMilestone` enfant d'un projet.
- Auto-positionnement (`max(position) + 1`) à la création.
- Gestion automatique de `completed_at` au passage en `done` (et réinitialisation si on quitte `done`).
- Endpoint dédié de **réordonnancement par batch** (`PATCH …/milestones/reorder`) en transaction.
- Route binding scoppé : un milestone qui n'appartient pas au projet de l'URL renvoie 404.

### Tableau de tâches : tasks

- CRUD complet d'une `Task` enfant d'un projet, optionnellement rattachée à une milestone.
- Filtres `status`, `priority`, `project_milestone_id`, recherche `title`.
- Auto-positionnement et `completed_at` géré automatiquement.
- Endpoint dédié de **réordonnancement par batch** (`PATCH …/tasks/reorder`) en transaction.
- Route binding scoppé : une tâche d'un autre projet renvoie 404 (anti-leak).

### Progression projet

- Service dédié `ProjectProgressService` qui calcule à la volée :
  - Compteurs par statut (`todo`, `in_progress`, `done`)
  - `overdue_tasks` (tâches non terminées avec `due_date < today`)
  - `completion_rate` global (basé sur les tâches, source de vérité)
  - `total_milestones` / `completed_milestones`
  - `next_due_task` et `next_due_milestone`
  - Indicateur `is_overdue` par projet
- Endpoint dédié `GET /api/projects/{project}/progress` avec décomposition **par milestone** (taux de complétion individuel, `null` si la milestone n'a aucune tâche).
- Bloc `progress` également inliné dans `GET /api/projects/{project}` pour éviter un appel supplémentaire.
- `GET /api/projects` expose `tasks_count` et `completed_tasks_count` via `withCount` pour afficher l'avancement directement dans la liste.

### Activity log

- Implémentation maison alignée sur la convention `singulier` du projet (table `activity_log`).
- Observers `Project` / `ProjectMilestone` / `Task` câblés via l'attribut `#[ObservedBy]` (Laravel 12 idiomatique).
- Événements tracés :
  - `project.created`, `project.status_changed`, `project.health_changed`, `project.deleted`
  - `milestone.created`, `milestone.status_changed`, `milestone.completed`, `milestone.deleted`
  - `task.created`, `task.status_changed`, `task.started`, `task.completed`, `task.milestone_attached`, `task.milestone_detached`, `task.milestone_changed`, `task.deleted`
- `customer_id` et `project_id` **dénormalisés** sur chaque ligne pour des reads de timeline en O(log n) via un index composite.
- Acteur résolu automatiquement (`Customer` connecté → `User` Filament → `null`).
- Snapshot du label dans `properties.label` pour rester lisible même après suppression du sujet.
- Helper `ActivityLogger::withoutLogging(fn () => …)` pour les seeders / imports massifs.
- Endpoint `GET /api/projects/{project}/activity` paginé desc.

### Dashboard agrégé

- Endpoint unique `GET /api/dashboard` qui retourne en un seul appel :
  - **`stats`** — projets actifs / terminés / en alerte / critiques, tâches en retard / dues aujourd'hui, milestones à venir, taux de complétion global
  - **`priorities`** — top tâches en retard, dues du jour, milestones imminentes, projets à risque
  - **`projects`** — synthèse des projets actifs / planifiés triée par échéance avec progression embarquée
  - **`recent_activity`** — 15 dernières lignes du journal d'activité du customer
- Pensé pour la page dashboard du front Next.js : un seul appel suffit pour peindre l'écran complet.
- **Ownership par construction** : toutes les requêtes du `DashboardService` JOIN sur `client.customer_id`, donc aucune fuite de données possible entre customers.

### Back-office Filament

- Ressources Filament pour `Client`, `Project`, `ProjectMilestone`, `Task`, `User`.
- Relation managers pour gérer les milestones / tâches directement depuis la fiche projet.
- Compte admin par défaut provisionné par le seeder `AdminUserSeeder`.

### Documentation API auto-générée

- Documentation HTML, **OpenAPI 3.0.3** et **collection Postman** générées via Scribe.
- Groupes : `Customer Authentication`, `Client Management`, `Project Management`, `Project Milestones`, `Project Tasks`, `Project Progress`, `Project Activity`, `Dashboard`.
- Disponible sur **https://api.ops-board.dev.localhost/docs**.

### Tests

- **208 tests Pest** couvrant les CRUDs, le réordonnancement, l'auth Sanctum SPA, la progression, l'activity log, le dashboard et l'isolation cross-customer.
- `RefreshDatabase` à chaque test, factories chaînables (`forCustomer`, `forProject`, `forMilestone`).

## Modèle métier

```
Customer  ──< Client  ──< Project  ──< ProjectMilestone
                                   │
                                   └──< Task ──> ProjectMilestone (optionnel)
```

| Entité | Rôle | Table |
|---|---|---|
| `User` | Admin Filament (back-office) | `user` |
| `Customer` | Utilisateur authentifié de l'app | `customer` |
| `Client` | Client métier géré par un customer | `client` |
| `Project` | Projet rattaché à un client | `project` |
| `ProjectMilestone` | Jalon d'un projet | `project_milestone` |
| `Task` | Unité de travail d'un projet, éventuellement liée à une milestone | `task` |
| `ActivityLog` | Journal d'événements scoppé par customer | `activity_log` |

> Convention de nommage : **toutes les tables sont au singulier**. Le pluriel n'est jamais utilisé, y compris pour la table d'activité.

La chaîne d'ownership est strictement enforcée :
- Un `Customer` ne voit que ses `Client`.
- Un `Client` n'expose que ses `Project`.
- Un `Project` n'expose que ses `ProjectMilestone` et ses `Task`.
- Une `Task` n'est jamais détachable de son `Project`, mais peut être (dé)liée à une `ProjectMilestone` du **même** projet.

## Architecture

| Dossier | Rôle |
|---|---|
| `app/Http/Controllers/Api/` | Controllers REST organisés par domaine |
| `app/Http/Requests/` | Form Requests + métadonnées Scribe |
| `app/Http/Resources/` | API Resources |
| `app/Services/` | Services métier (`ProjectProgressService`, `DashboardService`, `Activity\ActivityLogger`) |
| `app/Observers/` | Observers d'activity logging |
| `app/Policies/` | Policies (User bypass + Customer ownership) |
| `app/Enums/` | Enums backed strings (`TaskStatus`, `ProjectStatus`, `ProjectHealth`, …) |
| `app/Filament/` | Ressources Filament |
| `routes/api.php` | Routes API montées sur `api/*` |
| `bootstrap/app.php` | Middleware + `statefulApi()` Sanctum |
| `database/migrations/` | Migrations (singulier) |

L'auth client utilise le pattern **Sanctum SPA** : les sessions reposent sur un cookie partagé sur le domaine parent `.ops-board.dev.localhost`. Le front et l'API doivent vivre sur des sous-domaines de cette racine pour que le cookie de session soit accepté.

## Démarrage

Le projet tourne dans Docker Compose, derrière un reverse-proxy Traefik externe.

### Prérequis

- Docker + Docker Compose
- Un network Docker `proxy` partagé avec Traefik (à créer une fois) :
  ```bash
  docker network create proxy
  ```
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

## Authentification Sanctum SPA

Configuration côté `.env` :

| Variable | Valeur |
|---|---|
| `SESSION_DOMAIN` | `.ops-board.dev.localhost` |
| `SESSION_SAME_SITE` | `lax` |
| `SESSION_SECURE_COOKIE` | `true` |
| `SANCTUM_STATEFUL_DOMAINS` | `app.ops-board.dev.localhost` |
| `CORS_ALLOWED_ORIGINS` | `https://app.ops-board.dev.localhost` |

Côté front, avant toute requête mutante :

1. `GET https://api.ops-board.dev.localhost/sanctum/csrf-cookie` (avec `credentials: 'include'`)
2. Envoyer les requêtes suivantes avec `credentials: 'include'` et le header `X-XSRF-TOKEN` (axios le fait automatiquement à partir du cookie `XSRF-TOKEN`)

## Endpoints API

### Authentification

| Méthode | URI | Description |
|---|---|---|
| `POST` | `/api/register` | Crée un customer et ouvre une session |
| `POST` | `/api/login` | Authentifie un customer existant |
| `GET` | `/api/me` | Retourne le customer connecté |
| `POST` | `/api/logout` | Termine la session |

### Clients

| Méthode | URI | Description |
|---|---|---|
| `GET` | `/api/clients` | Liste paginée + filtres `status` / `search` |
| `POST` | `/api/clients` | Crée un client |
| `GET` | `/api/clients/{client}` | Détail |
| `PUT` | `/api/clients/{client}` | Mise à jour |
| `DELETE` | `/api/clients/{client}` | Suppression |

### Projets

| Méthode | URI | Description |
|---|---|---|
| `GET` | `/api/projects` | Liste paginée + filtres + `tasks_count` / `completed_tasks_count` |
| `POST` | `/api/projects` | Crée un projet |
| `GET` | `/api/projects/{project}` | Détail enrichi (`progress` inliné) |
| `PUT` | `/api/projects/{project}` | Mise à jour |
| `DELETE` | `/api/projects/{project}` | Suppression |
| `GET` | `/api/projects/{project}/progress` | Progression détaillée + breakdown par milestone |
| `GET` | `/api/projects/{project}/activity` | Timeline d'activité paginée |

### Milestones

| Méthode | URI | Description |
|---|---|---|
| `GET` | `/api/projects/{project}/milestones` | Liste ordonnée |
| `POST` | `/api/projects/{project}/milestones` | Création |
| `GET` | `/api/projects/{project}/milestones/{milestone}` | Détail |
| `PUT` | `/api/projects/{project}/milestones/{milestone}` | Mise à jour |
| `DELETE` | `/api/projects/{project}/milestones/{milestone}` | Suppression |
| `PATCH` | `/api/projects/{project}/milestones/reorder` | Réordonnancement batch |

### Tasks

| Méthode | URI | Description |
|---|---|---|
| `GET` | `/api/projects/{project}/tasks` | Liste filtrable |
| `POST` | `/api/projects/{project}/tasks` | Création |
| `GET` | `/api/projects/{project}/tasks/{task}` | Détail |
| `PUT` | `/api/projects/{project}/tasks/{task}` | Mise à jour |
| `DELETE` | `/api/projects/{project}/tasks/{task}` | Suppression |
| `PATCH` | `/api/projects/{project}/tasks/reorder` | Réordonnancement batch |

### Dashboard

| Méthode | URI | Description |
|---|---|---|
| `GET` | `/api/dashboard` | Payload agrégé : `stats`, `priorities`, `projects`, `recent_activity` |

## Admin Filament

Panneau admin disponible sur **https://api.ops-board.dev.localhost/admin**.
Le seeder `AdminUserSeeder` provisionne un compte admin par défaut (cf. `database/seeders/AdminUserSeeder.php`).

## Documentation API (Scribe)

```bash
artisan scribe:generate
```

Sorties générées :
- HTML : **https://api.ops-board.dev.localhost/docs**
- OpenAPI 3.0.3 : `storage/app/private/scribe/openapi.yaml`
- Collection Postman : `storage/app/private/scribe/collection.json`

## Tests

```bash
artisan test                  # tous les tests
artisan test --compact        # sortie condensée
artisan test --filter=Dashboard
```

Les tests utilisent **Pest 3** + `RefreshDatabase`. La base de test est configurée dans `phpunit.xml`.

## Qualité de code

```bash
docker compose exec app vendor/bin/pint --dirty --format agent
```

Pint est configuré au preset Laravel.

## Structure du projet

```
api/
├── app/
│   ├── Enums/                       # Enums status / priority / health
│   ├── Filament/                    # Ressources back-office
│   ├── Http/
│   │   ├── Controllers/Api/         # Controllers REST par domaine
│   │   ├── Requests/                # Form Requests
│   │   └── Resources/               # API Resources
│   ├── Models/                      # Modèles Eloquent
│   ├── Observers/                   # Observers d'activity logging
│   ├── Policies/                    # Authorization
│   └── Services/                    # ProjectProgressService, DashboardService, Activity\ActivityLogger
├── bootstrap/app.php                # Middleware + routing
├── config/
│   ├── cors.php
│   ├── sanctum.php
│   └── scribe.php
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

---

## À propos

Ce projet fait partie d'un travail vitrine pour mon portfolio personnel : **[www.mengesjean.fr](https://www.mengesjean.fr)**.
Il sert à illustrer mes choix d'architecture côté Laravel — il n'est ni pensé ni destiné à un usage en production.
