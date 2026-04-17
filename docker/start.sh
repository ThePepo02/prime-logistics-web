#!/bin/sh

# Generar APP_KEY si no existe
php artisan key:generate --force

# Limpiar y cachear configuración son optimizaciones de rendimiento.
# Hace que laravel no los leas y los guardamos en el cache,
# lo que mejora el rendimiento al evitar la necesidad de cargar y procesar los archivos de configuración en cada solicitud.
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Arrancar servicios con supervisor
exec /usr/bin/supervisord -c /etc/supervisord.conf
