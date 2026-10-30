#!/bin/bash
set -e

# Ensure uploads and cache directories exist
mkdir -p /var/www/html/upload /var/www/html/cache /var/www/html/themes

# Fix permissions for web server
chown -R www-data:www-data /var/www/html/upload /var/www/html/cache /var/www/html/themes
chmod -R 775 /var/www/html/upload /var/www/html/cache

# Run Apache
exec apache2-foreground "$@"
