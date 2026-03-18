FROM php:8.3-apache
RUN apt-get update && apt-get install -y git unzip
RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer 
COPY ./src /var/www/html
EXPOSE 80
