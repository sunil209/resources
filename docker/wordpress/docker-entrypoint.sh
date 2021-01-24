#!/bin/bash
set -euo pipefail

# We need to have wp-content directory writable not only by files owner also by web server, so WordPress can keep
# temporary files there during image optimalization process
# Set secure file permissions for WP, server works on www-data user, files are owned by another user so:
mkdir -p /var/www/app/wp-content/uploads
find /var/www/app -type d -exec chmod 755 {} \;
find /var/www/app -type f -exec chmod 644 {} \;
chown -R www-data:www-data /var/www/app/wp-content/uploads

exec "$@"
