# Download base php
FROM php:8.2-fpm

# LABEL about the image
LABEL maintainer="ikpeba4real@gmail.com"
LABEL version="0.1"

# Disable Prompt During Packages Installation
ARG DEBIAN_FRONTEND=noninteractive


# Update Software repository
RUN apt update && apt -y upgrade &&  apt install -y software-properties-common && apt update 

# Install php-fpm and supervisord from  repository
RUN apt install -y supervisor libxrender-dev libfontconfig1 libxext6 libx11-dev \
    unzip libpng-dev libzip-dev zlib1g-dev curl mariadb-client libicu-dev && \
    rm -rf /var/lib/apt/lists/* && apt clean

# mbstring libonig-dev

# RUN  apt update && apt install php8.1-common
# RUN  apt update && apt install build-essential && apt update && apt install php-calendar

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql gd zip exif pcntl bcmath intl calendar

RUN docker-php-ext-configure calendar


# Define the ENV variable
ENV php_conf /etc/php/8.2/fpm/php.ini
ENV supervisor_conf /etc/supervisor/supervisord.conf

# Copy supervisor configuration
# COPY ./.docker/supervisord.conf ${supervisor_conf}

RUN mkdir /var/www/app

RUN mkdir -p /run/php && \
    chown -R www-data:www-data /var/www/app && \
    chown -R www-data:www-data /run/php


WORKDIR /var/www/app

# Install composer
COPY --from=composer:2.2.0 /usr/bin/composer /usr/local/bin/composer

# Define default command for the container
# CMD ["./.docker/start.sh"]

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
# COPY . /var/www/app

# Copy existing application directory permissions
COPY --chown=www:www . /var/www/app

# Change current user to www
# USER www

# Expose Port for the Application
EXPOSE 9000
CMD ["php-fpm"]