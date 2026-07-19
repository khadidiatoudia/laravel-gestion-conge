#!/bin/bash
set -e

export PORT="${PORT:-10000}"

# Génère la config nginx avec le bon port (Render fournit $PORT dynamiquement)
envsubst '${PORT}' < /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf

cd /var/www/html

if [ -z "$APP_KEY" ]; then
    echo "ERREUR: la variable d'environnement APP_KEY n'est pas définie."
    echo "Génère-la localement avec 'php artisan key:generate --show' et ajoute-la dans les variables d'environnement Render."
    exit 1
fi

php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan migrate --force

exec supervisord -c /etc/supervisord.conf
