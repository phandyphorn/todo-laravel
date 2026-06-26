# FROM php:8.3-fpm

# # Install system dependencies
# RUN apt-get update && apt-get install -y \
#     git \
#     curl \
#     libpq-dev \
#     libonig-dev \
#     libxml2-dev \
#     zip \
#     unzip \
#     netcat-openbsd

# # Install PHP extensions
# RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath

# # Install Composer
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# WORKDIR /app

# # Copy project files
# COPY . .

# # Install Laravel dependencies
# RUN composer install --no-interaction

# # Set permissions
# RUN chown -R www-data:www-data /app

# # Copy entrypoint script
# COPY --chmod=+x ./docker-entrypoint.sh /usr/local/bin/

# EXPOSE 9000

# ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
# CMD ["php-fpm"]

# PostgreSQL

FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \        
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \        
    pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install