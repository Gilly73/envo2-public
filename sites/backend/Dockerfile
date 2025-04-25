FROM php:8.2-fpm

RUN find /etc/apt -type f -name "*.list" -exec sed -i 's|http://deb.debian.org/debian|https://deb.debian.org/debian|g' {} +

# Set up Debian repositories securely
RUN apt-get update --allow-releaseinfo-change && apt-get install -y \
    gnupg \
    debian-keyring \
    debian-archive-keyring \
    apt-transport-https \
    ca-certificates

#RUN apt-get update && apt-get add --no-cache \ --fix-missing \
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    git \
    zip \
    unzip \
    supervisor \
    bash \
    curl \
    sudo \
    nano \
    pkg-config \
    redis-tools \
    mariadb-client \
    iputils-ping \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring xml gd

RUN sed -i 's/listen = 127.0.0.1:9000/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/www.conf

# Copy the self‑signed certificate into the trusted CA directory
COPY nginx/certs/selfsigned.crt /usr/local/share/ca-certificates/selfsigned.crt

# Update the CA certificates so the self‑signed cert is trusted
RUN update-ca-certificates

# Install Redis PHP extension
RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html/backend

COPY sites/backend/. /var/www/html/backend

COPY sites/backend/php.ini /usr/local/etc/php/php.ini

RUN composer install --no-interaction --no-dev --optimize-autoloader

# Create a non-root user for container operations
RUN groupadd -g 1000 appuser && \
    useradd -u 1000 -g appuser -m -s /bin/bash appuser

# Configure permissions for the application
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/backend/storage /var/www/html/backend/bootstrap/cache

# Create log directory and set permissions for supervisord
RUN mkdir -p /var/log/supervisor && \
    chown -R root:root /var/log/supervisor && \
    chmod -R 775 /var/log/supervisor


COPY sites/backend/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 9000

# Switch back to root to run supervisord
USER root
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Switch to non-root user for container operations
#USER appuser - this is prevented backend from running supervisord php-fpm as a root user so I change the user when I'm in the container for now