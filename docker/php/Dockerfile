FROM php:8.2.0-fpm

ARG user=dancostinel
ARG uid=1030

RUN apt-get update && apt-get install apt-file -y && apt-file update && apt-get install vim -y --no-install-recommends \
        git \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
    && docker-php-ext-install \
        zip \
        intl \
        mysqli \
        pdo pdo_mysql

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -P /home/$user/.composer && chown -R $user:$user /home/$user
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
WORKDIR /var/www/origin-data-app/
COPY --from=composer /usr/bin/composer /usr/bin/composer
USER $user