FROM php:8.4

RUN apt-get update -y && apt-get install -y openssl zip unzip git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get update && apt-get install -y default-mysql-client default-libmysqlclient-dev
RUN docker-php-ext-install pdo pdo_mysql

COPY . /app
WORKDIR /app
RUN composer install
CMD php artisan serve --host=0.0.0.0 --port=8000
EXPOSE 8000