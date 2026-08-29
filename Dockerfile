FROM php:8.3-apache

# Extensions used by JIBAS: mysqli (DB), gd (image resize/thumbs), pdo (some libs)
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpng-dev libjpeg-dev libfreetype6-dev libzip-dev unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install mysqli pdo_mysql gd zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# JIBAS-specific PHP ini: short tags REQUIRED, silence deprecations from
# legacy code, auto-prepend each() polyfill (removed in PHP 8, used by
# vendored jpgraph/ezpdf/barcode libraries).
RUN printf '%s\n' \
    'short_open_tag=On' \
    'error_reporting=E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING' \
    'display_errors=On' \
    'auto_prepend_file=/var/www/html/docker/each_polyfill.php' \
    > /usr/local/etc/php/conf.d/jibas.ini

# AllowOverride All so per-directory .htaccess (Options -Indexes, etc.) works
RUN printf '%s\n' \
    '<Directory /var/www/html>' \
    '    AllowOverride All' \
    '    Require all granted' \
    '</Directory>' \
    > /etc/apache2/conf-available/jibas.conf \
    && a2enconf jibas

WORKDIR /var/www/html
