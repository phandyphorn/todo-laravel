FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    gettext-base \
    && rm -rf /var/lib/apt/lists/* \
    && rm -f /etc/nginx/sites-enabled/default

RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Now copy the rest of the app
COPY . .

# Safety net: remove any cached config/route/view files that may have been
# accidentally committed locally — these would override Railway's runtime env vars
RUN rm -f bootstrap/cache/config.php \
    bootstrap/cache/routes-v7.php \
    bootstrap/cache/services.php \
    bootstrap/cache/packages.php

# Re-run composer's post-install scripts now that full app code is present
RUN composer dump-autoload --optimize

# Nginx config template (uses $PORT at runtime)
COPY docker/nginx/default.conf.template /etc/nginx/templates/default.conf.template

# Supervisor config
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Entrypoint to substitute $PORT, clear/cache config at runtime, migrate, and start supervisor
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]