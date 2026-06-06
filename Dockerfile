FROM php:8.3-apache

# Instalar dependencias del sistema y Node.js
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    zip unzip git curl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Instalar extensiones PHP y Apache
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 1. Copiamos TODOS los archivos primero
COPY . .

# 2. Ahora sí instalamos dependencias (ya que 'artisan' está presente, no fallará)
RUN composer install --no-dev --optimize-autoloader

# 3. Instalamos frontend
RUN npm ci && npm run build

# Permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80