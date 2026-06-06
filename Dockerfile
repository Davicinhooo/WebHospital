FROM php:8.3-apache

# 1. Instalar dependencias del sistema y Node.js (necesario para Vite)
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    zip unzip git curl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 2. Instalar extensiones PHP y Apache
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && a2enmod rewrite

# 3. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 4. Copiamos TODOS los archivos de tu proyecto
COPY . .

# 5. Instalamos dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# 6. Instalamos dependencias de Node y construimos Vite
RUN npm install --legacy-peer-deps
RUN npm run build

# 7. Permisos de carpetas
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80