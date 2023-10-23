FROM php:8.1-fpm

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip nodejs npm \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

WORKDIR /var/www/caestats

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
#RUN mv /usr/bin/symfony /usr/local/bin/symfony
RUN git config --global user.email "adrien.orsier@gmail.com" \ 
    && git config --global user.name "Zimfg"
COPY . ./
RUN mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
RUN composer install
RUN npm install
RUN npm run build
