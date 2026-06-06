# Usamos una imagen que ya trae Apache integrado para servir tu web
FROM php:8.3-apache

# 1. Instalar herramientas del sistema y extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 2. Habilitar Apache mod_rewrite (vital para las rutas de Laravel)
RUN a2enmod rewrite

# 3. Instalar Composer (para las dependencias de Laravel)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Preparar la carpeta del proyecto
WORKDIR /var/www/html
COPY . .

# 5. Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# 6. Dar permisos a las carpetas de caché
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Cambiar el DocumentRoot de Apache para que apunte a la carpeta 'public'
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# 8. Render asigna el puerto automáticamente, Apache ya escucha en el puerto correcto
EXPOSE 80