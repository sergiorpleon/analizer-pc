FROM php:8.2-apache

# 1. Dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*

# 2. Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Directorio de trabajo
WORKDIR /var/www/html

# 4. COPIAMOS TODO EL PROYECTO PRIMERO
# Esto asegura que composer.json esté presente sí o sí
COPY . .

# 5. Ejecutamos la instalación de dependencias
# Agregamos --verbose para ver el error real si falla
RUN composer install --no-interaction --optimize-autoloader --no-scripts --ignore-platform-reqs --verbose

# 6. Configurar Apache para usar public/ como DocumentRoot
RUN a2enmod rewrite && chown -R www-data:www-data /var/www/html

# Configurar DocumentRoot
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

# Permitir .htaccess
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf