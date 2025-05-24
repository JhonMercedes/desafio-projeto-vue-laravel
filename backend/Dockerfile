FROM php:8.2-cli

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    unzip \
    zip \
    curl \
    git \
    && docker-php-ext-install pdo pdo_mysql
    
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

COPY . .

RUN cp .env.example .env || true

RUN composer install

EXPOSE 8000
#CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]