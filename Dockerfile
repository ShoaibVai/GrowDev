# GrowDev Laravel Backend Docker Configuration
# Build: docker build -t growdev:latest .
# Run: docker run -p 8000:8000 growdev:latest

FROM php:8.2-apache

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    git \
    zip \
    unzip \
    vim \
    sqlite3 \
    libsqlite3-dev \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo \
    pdo_sqlite \
    pdo_pgsql \
    pdo_mysql \
    zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /app

# Create necessary directories
RUN mkdir -p storage/framework/{cache,sessions,views} \
    && mkdir -p storage/logs \
    && chmod -R 777 storage bootstrap/cache

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Configure Apache
RUN a2enmod rewrite
COPY <<EOF /etc/apache2/sites-available/000-default.conf
<VirtualHost *:80>
    DocumentRoot /app/public

    <Directory /app/public>
        AllowOverride All
        Require all granted
    </Directory>

    <Directory /app>
        AllowOverride All
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

# Health check endpoint
RUN echo "<?php echo 'OK';" > /app/public/health.php

# Expose port
EXPOSE 80

# Set environment
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

# Start Apache
CMD ["apache2-foreground"]
