FROM php:8.4-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libicu-dev libpq-dev libzip-dev \
    && docker-php-ext-install intl pdo_pgsql zip opcache \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*
RUN echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY vhost.conf /etc/apache2/sites-available/000-default.conf
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html

ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]