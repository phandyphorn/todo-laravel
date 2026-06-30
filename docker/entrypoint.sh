#!/bin/sh
set -e

# Wait isn't strictly needed on Railway since DB is usually already up,
# but migrations need to run before serving traffic
php artisan migrate --force

# Cache config/routes for production performance
php artisan config:cache
php artisan route:cache

php-fpm -D
nginx -g 'daemon off;'