FROM php:8.2-apache

# 1. Instalamos extensiones para Postgres
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# 2. Definimos la ruta REAL donde está tu index.php
# Según tu estructura es: /var/www/html/src/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/src/public

# 3. Cambiamos la configuración de Apache para que apunte a esa carpeta
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Aseguramos permisos totales para evitar el Forbidden
RUN echo "<Directory ${APACHE_DOCUMENT_ROOT}>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf

# 5. Habilitamos el módulo rewrite y corregimos permisos de archivos
RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# El contenedor arranca Apache automáticamente