FROM php:8.2-apache

# Instalar extensiones PHP necesarias y mysql-client para entrypoint
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Configurar Apache para permitir .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copiar código de la aplicación
COPY . /var/www/html/

# Crear directorio de logs
RUN mkdir -p /var/www/html/logs \
    && chown -R www-data:www-data /var/www/html/logs \
    && chmod -R 755 /var/www/html/logs

# Configurar permisos para assets/images
RUN chown -R www-data:www-data /var/www/html/assets/images \
    && chmod -R 755 /var/www/html/assets/images

# Copiar endpoint de métricas
COPY monitoring/php-exporter/metrics.php /var/www/html/metrics.php
RUN chown www-data:www-data /var/www/html/metrics.php \
    && chmod 644 /var/www/html/metrics.php

# Copiar y configurar entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
# Convertir CRLF a LF (formato Unix) y asegurar permisos de ejecución
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && \
    chmod +x /usr/local/bin/entrypoint.sh

# Exponer puerto 80
EXPOSE 80

# Usar entrypoint para esperar a MySQL
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
