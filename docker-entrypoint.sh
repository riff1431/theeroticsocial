#!/bin/bash
set -e

# Ensure uploads and cache directories exist
mkdir -p /var/www/html/upload /var/www/html/cache /var/www/html/themes

# Clean stale template caches
rm -f /var/www/html/cache/*.tpl /var/www/html/cache/*/*.tpl 2>/dev/null || true

# Sync repo/seed uploads into persistent volume without overwriting existing live uploads
if [ -d /var/www/upload_seed ]; then
    rsync -au --ignore-existing /var/www/upload_seed/ /var/www/html/upload/ 2>/dev/null || cp -rn /var/www/upload_seed/. /var/www/html/upload/ 2>/dev/null || true
fi

# Fix permissions for web server
chown -R www-data:www-data /var/www/html/upload /var/www/html/cache /var/www/html/themes
chmod -R 775 /var/www/html/upload /var/www/html/cache

# Run Apache
exec apache2-foreground "$@"
