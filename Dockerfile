FROM php:8.4-fpm

# Аргументи для user/group
ARG WWWGROUP=1000
ARG WWWUSER=1000

# Встановлення системних залежностей
RUN apt-get update && \
    apt-get install -y \
        libicu-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        unzip \
        git \
        curl \
        default-mysql-client \
        supervisor && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Встановлення PHP розширень
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install \
        pdo_mysql \
        intl \
        zip \
        gd \
        mbstring \
        xml \
        bcmath \
        opcache \
        pcntl

# Встановлення Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Xdebug конфігурація
ARG XDEBUG_MODES="debug"
ARG REMOTE_HOST="host.docker.internal"
ARG REMOTE_PORT=9003

ENV MODES=$XDEBUG_MODES
ENV CLIENT_HOST=$REMOTE_HOST
ENV CLIENT_PORT=$REMOTE_PORT

# Встановлення Xdebug
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

# Створення користувача
RUN groupadd --force -g $WWWGROUP laravel && \
    useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u $WWWUSER laravel

# Робоча директорія
WORKDIR /var/www

# Права доступу
RUN chown -R laravel:laravel /var/www

# Експорт портів
EXPOSE 9000

# Запуск PHP-FPM
CMD ["php-fpm"]
