FROM php:8.1-apache

# Install required system packages and libraries
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    ffmpeg \
    unzip \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Configure & install PHP extensions for WoWonder
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        mysqli \
        pdo_mysql \
        gd \
        zip \
        mbstring \
        exif \
        fileinfo \
        bcmath \
        opcache

# Enable Apache rewrite and headers modules
RUN a2enmod rewrite headers

# Allow .htaccess overrides in Apache
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Production PHP settings for WoWonder (large file uploads, reasonable timeouts)
RUN { \
    echo 'upload_max_filesize = 500M'; \
    echo 'post_max_size = 500M'; \
    echo 'memory_limit = 512M'; \
    echo 'max_execution_time = 600'; \
    echo 'max_input_time = 600'; \
    echo 'max_input_vars = 5000'; \
    echo 'output_buffering = Off'; \
    echo 'allow_url_fopen = On'; \
    echo 'display_errors = Off'; \
    echo 'log_errors = On'; \
} > /usr/local/etc/php/conf.d/wowonder.ini

WORKDIR /var/www/html

# Copy source code
COPY . /var/www/html

# Copy and setup entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Create required folders and set base permissions
RUN mkdir -p /var/www/html/upload /var/www/html/cache /var/www/html/themes \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/upload /var/www/html/cache

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
