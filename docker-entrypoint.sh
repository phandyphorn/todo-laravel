#!/bin/bash

# Wait for MySQL to be ready
while ! nc -z mysql 3306; do
  echo "Waiting for MySQL at mysql:3306..."
  sleep 2
done

echo "MySQL is up! Running migrations..."

# Run migrations
php artisan migrate --force

exec "$@"