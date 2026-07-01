#!/bin/sh
set -e

# Always clear any baked-in cached config, rebuild fresh using Railway's runtime env vars
php artisan config:clear
php artisan config:cache

# Run migrations automatically on every deploy
php artisan migrate --force

# Substitute $PORT into the Nginx config template
envsubst '${PORT}' < /etc/nginx/templates/default.conf.template > /etc/nginx/conf.d/default.conf

echo "Starting Nginx on port ${PORT}"

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf