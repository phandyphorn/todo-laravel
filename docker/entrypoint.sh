#!/bin/sh
set -e

php artisan config:clear
php artisan config:cache

# Substitute $PORT (and any other env vars) into the Nginx config template
envsubst '${PORT}' < /etc/nginx/templates/default.conf.template > /etc/nginx/conf.d/default.conf

# Optional but useful for debugging Railway deploys
echo "Starting Nginx on port ${PORT}"

# Hand off to Supervisord as PID 1
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf