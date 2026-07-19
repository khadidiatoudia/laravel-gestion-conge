# Déploiement sur Render

Ce projet est prêt à être déployé sur Render via Docker + PostgreSQL managé.

## Fichiers ajoutés

- `Dockerfile` — image PHP-FPM + Nginx + Supervisor
- `docker/nginx.conf.template`, `docker/supervisord.conf`, `docker/start.sh`
- `.dockerignore`
- `render.yaml` — blueprint (déploiement en un clic, service web + base PostgreSQL)

## Option A — Déploiement via Blueprint (le plus rapide)

1. Génère une clé d'application en local :
   ```bash
   php artisan key:generate --show
   ```
   Copie la valeur affichée (commence par `base64:...`).

2. Pousse ces nouveaux fichiers sur GitHub :
   ```bash
   git add Dockerfile docker/ .dockerignore render.yaml DEPLOY_RENDER.md
   git commit -m "Ajout config déploiement Render (Docker + PostgreSQL)"
   git push origin main
   ```

3. Sur [render.com](https://dashboard.render.com), clique sur **New > Blueprint**, connecte ton dépôt GitHub `rose2jesus/laravel-gestion-conge`.

4. Render détecte `render.yaml` et propose de créer :
   - le service web `gestion-conges` (Docker)
   - la base `gestion-conges-db` (PostgreSQL gratuite)

5. Quand Render demande la valeur de `APP_KEY` (marquée `sync: false`), colle la clé générée à l'étape 1.

6. Clique sur **Apply** — Render build l'image Docker, provisionne la base, connecte les deux automatiquement, exécute les migrations au démarrage (`php artisan migrate --force` dans `start.sh`), puis lance le service.

7. Une fois déployé, va dans les seeders si besoin — connecte-toi au Shell Render (`Shell` dans le dashboard du service) et lance :
   ```bash
   php artisan db:seed --force
   ```
   pour créer les comptes admin/RH initiaux.

## Option B — Déploiement manuel (sans render.yaml)

1. **New > PostgreSQL** → crée une base gratuite, note son nom interne (ex. `gestion-conges-db`).
2. **New > Web Service** → connecte le dépôt GitHub, choisis **Docker** comme runtime (Render détecte le `Dockerfile` automatiquement).
3. Dans l'onglet **Environment** du service web, ajoute les variables :

   | Clé | Valeur |
   |---|---|
   | `APP_NAME` | Gestion Congés Université |
   | `APP_ENV` | production |
   | `APP_DEBUG` | false |
   | `APP_LOCALE` | fr |
   | `APP_KEY` | (coller le résultat de `php artisan key:generate --show`) |
   | `LOG_CHANNEL` | stderr |
   | `SESSION_DRIVER` | database |
   | `CACHE_STORE` | database |
   | `QUEUE_CONNECTION` | database |
   | `DB_CONNECTION` | pgsql |
   | `DB_HOST` | (valeur "Host" interne de ta base Postgres) |
   | `DB_PORT` | (valeur "Port", généralement 5432) |
   | `DB_DATABASE` | (nom de la base) |
   | `DB_USERNAME` | (utilisateur de la base) |
   | `DB_PASSWORD` | (mot de passe de la base) |

   Toutes ces valeurs sont visibles dans le dashboard de ta base PostgreSQL Render (section **Connections**).

4. Déploie. Render construit l'image et lance `docker/start.sh`, qui migre la base et démarre nginx/php-fpm.

## Points importants

- **Pas de stockage de fichiers utilisateurs** dans l'app (pas d'upload détecté), donc pas besoin de disque persistant Render — le plan gratuit suffit.
- **Sessions/cache/queue** passent par la base de données (déjà configuré), donc aucune donnée n'est perdue lors des redéploiements ou redémarrages du conteneur.
- Le plan gratuit Render met le service en veille après 15 minutes d'inactivité ; la première requête après une veille prend 30–60s (cold start).
- Pense à créer les comptes utilisateurs via `php artisan db:seed --force` (ou manuellement) après le premier déploiement, la base PostgreSQL démarrant vide.
- `APP_URL` : Render te donne une URL du type `https://gestion-conges.onrender.com` après le premier déploiement — pense à la renseigner en variable d'environnement si l'app en a besoin pour générer des liens absolus.
