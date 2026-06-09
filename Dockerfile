FROM php:8.2-cli

WORKDIR /app

# dependências do sistema
RUN apt-get update && apt-get install -y \
    git curl unzip zip libzip-dev libpq-dev \
    nodejs npm \
    && docker-php-ext-install pdo pdo_pgsql zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# copiar projeto
COPY . .

# instalar backend
RUN composer install --no-interaction --optimize-autoloader

# instalar frontend (Vite)
RUN npm install
RUN npm run build

# permissões Laravel
RUN chmod -R 777 storage bootstrap/cache

# otimizações Laravel
RUN php artisan config:clear
RUN php artisan cache:clear

EXPOSE 10000

CMD php artisan serve --host 0.0.0.0 --port 10000
