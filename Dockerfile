FROM php:8.2-apache

# 1. Dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip \
    && rm -rf /var/lib/apt/lists/*

# 2. Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Directorio de trabajo
WORKDIR /var/www/html

# 4. Copiar composer.json primero
COPY composer.json ./

# 5. Instalar dependencias
RUN composer install --no-interaction --no-scripts --ignore-platform-reqs || true

# 6. Copiar el resto del proyecto
COPY . .

# 7. Regenerar autoloader
RUN composer dump-autoload -o || true

# 8. Configurar Apache para usar public/ como DocumentRoot
RUN a2enmod rewrite && chown -R www-data:www-data /var/www/html

# Configurar DocumentRoot
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|DocumentRoot /var/www/html/public|DocumentRoot /var/www/html/public|g' /etc/apache2/apache2.conf

# Permitir .htaccess y configurar permisos
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# Dar permisos
RUN chmod -R 755 /var/www/html/public