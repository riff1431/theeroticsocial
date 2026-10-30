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
    rsync \
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

# Enable Apache performance & rewrite modules
RUN a2enmod rewrite headers expires deflate mime filter

# Allow .htaccess overrides in Apache
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Production PHP & OPcache settings for high performance WoWonder
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
    echo 'opcache.enable = 1'; \
    echo 'opcache.enable_cli = 0'; \
    echo 'opcache.memory_consumption = 256'; \
    echo 'opcache.interned_strings_buffer = 16'; \
    echo 'opcache.max_accelerated_files = 20000'; \
    echo 'opcache.revalidate_freq = 60'; \
    echo 'opcache.validate_timestamps = 1'; \
    echo 'opcache.save_comments = 1'; \
    echo 'opcache.fast_shutdown = 1'; \
} > /usr/local/etc/php/conf.d/wowonder.ini

WORKDIR /var/www/html

# Copy source code
COPY . /var/www/html

# Keep a seed copy of repo uploads so the entrypoint can sync new files into persistent volume
RUN cp -a /var/www/html/upload /var/www/upload_seed

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
