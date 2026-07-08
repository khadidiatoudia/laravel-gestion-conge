# Installation - Plateforme Gestion des Absences et Congés

## Prérequis
- PHP >= 8.2 avec extensions: pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json, bcmath, gd
- Composer >= 2.0
- MySQL >= 8.0 / MariaDB >= 10.5
- Node.js (optionnel, pour assets frontend)

## Étapes d'installation

### 1. Créer le projet Laravel
```bash
composer create-project laravel/laravel gestion-conges
cd gestion-conges
```

### 2. Installer les dépendances
```bash
composer require barryvdh/laravel-dompdf
```

### 3. Configurer la base de données
Créer la base de données MySQL :
```sql
CREATE DATABASE gestion_conges CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Éditer le fichier `.env` :
```
APP_NAME="Gestion Congés Université"
APP_URL=http://localhost:8000
APP_LOCALE=fr

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_conges
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

### 4. Copier les fichiers du projet
Copier tous les fichiers fournis dans les dossiers correspondants :
- `app/Models/` → modèles Eloquent
- `app/Services/` → service de calcul des congés
- `app/Http/Controllers/` → contrôleurs
- `database/migrations/` → migrations
- `database/seeders/` → seeders
- `resources/views/` → vues Blade
- `routes/web.php` → routes

### 5. Générer la clé d'application
```bash
php artisan key:generate
```

### 6. Exécuter les migrations et seeders
```bash
php artisan migrate --seed
```

### 7. (Optionnel) Lier le stockage
```bash
php artisan storage:link
```

### 8. Lancer le serveur de développement
```bash
php artisan serve
```

L'application sera accessible sur http://localhost:8000

### 9. Se connecter
| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | admin@universite.sn | password |
| Gestionnaire RH | rh@universite.sn | password |

## Structure du projet

```
app/
  Http/Controllers/
    AuthController.php       - Authentification
    DashboardController.php  - Tableau de bord
    AgentController.php      - CRUD agents
    AbsenceController.php    - Saisie absences
    CongeController.php      - Saisie congés + calcul dates
    RapportController.php    - Génération rapports PDF
    JourFerieController.php  - Gestion jours fériés
  Models/
    User.php
    Agent.php                - Logique calcul congés/absences
    Absence.php
    Conge.php
    JourFerie.php
    LieuAffectation.php
  Services/
    CongeCalculator.php      - Calcul dates ouvrables (lundi-samedi hors fériés)
database/
  migrations/               - 6 migrations
  seeders/
    DatabaseSeeder.php       - Données initiales (users, lieux, jours fériés)
resources/views/
  layouts/app.blade.php     - Layout Bootstrap 5 avec sidebar
  auth/login.blade.php
  dashboard/index.blade.php
  agents/{index,show,create,edit}.blade.php
  absences/create.blade.php
  conges/create.blade.php
  rapports/{index,tableau,pdf,pdf_all}.blade.php
  jours_feries/index.blade.php
routes/web.php
```

## Règles métier implémentées

- **Congés annuels** : 24 jours (2j/mois), pro-rata si < 12 mois de service
- **Bonus enfants** : +1 jour/enfant pour les femmes uniquement
- **Congés dus** = reportés N-1 + annuels + bonus enfants + exceptionnels Recteur - absences déductibles
- **Plafond** : 72 jours maximum (loi travail, cap 3 ans)
- **Jours ouvrables** : lundi au samedi, hors jours fériés sénégalais
- **Règle vendredi** : si cessation un vendredi, le comptage commence le lundi suivant
- **Date reprise** = date cessation + 1 jour ouvrable + nombre de jours à prendre
- **Absences exceptionnelles** (mariage, baptême, décès) : non déduites du congé
- **Jours restants** = congés dus - (jours à prendre + absences déductibles)

## Dépendances clés

```json
{
  "require": {
    "php": "^8.2",
    "laravel/framework": "^11.0",
    "barryvdh/laravel-dompdf": "^2.2"
  }
}
```

## Déploiement en production

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan db:seed --force
```

Configurer votre serveur web (Apache/Nginx) pour pointer vers le dossier `public/`.
