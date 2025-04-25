FROM php:8.2-fpm

# 1. Set up Debian repositories securely and install system dependencies
RUN apt-get update --allow-releaseinfo-change && \
    apt-get install -y \
      gnupg \
      debian-keyring \
      debian-archive-keyring \
      apt-transport-https \
      ca-certificates \
      curl && \
    apt-get update && apt-get install -y --no-install-recommends \
      git \
      zip \
      unzip \
      supervisor \
      lsof \
      sudo && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# 2. Update php-fpm config to listen on all interfaces
RUN sed -i 's/listen = 127.0.0.1:9000/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/www.conf

# 3. Copy self‑signed certificate and update CA certificates
COPY nginx/certs/selfsigned.crt /usr/local/share/ca-certificates/selfsigned.crt
RUN update-ca-certificates

# 4. Install Node.js (specific version for your platform)
RUN apt-get update && apt-get install -y curl && \
    curl -fsSL https://nodejs.org/dist/v20.11.1/node-v20.11.1-linux-arm64.tar.xz -o node.tar.xz && \
    tar -xJf node.tar.xz -C /usr/local --strip-components=1 && \
    rm node.tar.xz && \
    node -v && npm -v

# 5. Install Composer (from the official composer image)
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# 6. Set working directory
WORKDIR /var/www/html/frontend

# 7. Copy the entire frontend source code into the image
COPY sites/frontend/. /var/www/html/frontend/

# 8. Install Node.js dependencies (node_modules will be created inside the image)
#RUN npm install - use this if lock file is not present
#use this when lock file is present - it will only run what is in the lock file
RUN npm ci 

# 9. Install PHP dependencies (this will run artisan scripts if present)
RUN composer install --no-interaction --no-dev --optimize-autoloader


RUN mkdir -p /home/appuser/.npm

# 11. Set permissions for Laravel storage and cache directories (if used)
RUN chown -R www-data:www-data /var/www/html/frontend/storage /var/www/html/frontend/bootstrap/cache

# 12. Create a non-root user for container operations and change ownership of the entire folder
RUN groupadd -g 1000 appuser && \
    useradd -u 1000 -g appuser -m -s /bin/bash appuser && \
    chown -R appuser:appuser /var/www/html/frontend/node_modules && \
    chown -R appuser:appuser /var/www/html/frontend/public/build && \
    chown -R appuser:appuser /var/www/html/frontend && \
    chown -R appuser:appuser /home/appuser/.npm

# 13. Switch to non-root user
USER appuser

# 10. Build frontend assets (e.g. via Vite)
RUN npm run build

EXPOSE 9000

CMD ["php-fpm"]