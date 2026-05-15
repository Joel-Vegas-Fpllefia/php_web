FROM php:8.2-apache

# 1. Instalamos extensiones para Postgres
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# 2. Copiamos los archivos del repo al contenedor
COPY . /var/www/html/

# 3. Buscamos automáticamente dónde está el index.php y lo fijamos como Root
# Esto funcionará si está en la raíz, en /public o en /src/public
RUN if [ -f /var/www/html/src/public/index.php ]; then \
        export WEBROOT=/var/www/html/src/public; \
    elif [ -f /var/www/html/public/index.php ]; then \
        export WEBROOT=/var/www/html/public; \
    else \
        export WEBROOT=/var/www/html; \
    fi && \
    sed -ri -e "s!/var/www/html!${WEBROOT}!g" /etc/apache2/sites-available/*.conf && \
    sed -ri -e "s!/var/www/!${WEBROOT}!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Permisos críticos para evitar el Forbidden en Render
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

RUN a2enmod rewrite