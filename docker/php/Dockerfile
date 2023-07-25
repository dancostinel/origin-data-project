FROM php:8.2.0-fpm
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

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

RUN useradd -u 3030 dancostinel

RUN chmod -R 775 /var/www/origin-data-app/
RUN chown -R dancostinel:www-data /var/www/origin-data-app/
WORKDIR /var/www/origin-data-app/
COPY ../../. /var/www/origin-data-app/
USER dancostinel
RUN composer install