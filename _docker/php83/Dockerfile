FROM php:8.3.11-apache

# Install system library dependencies
RUN apt-get update
RUN apt-get install -y \
    git \
    g++ \
    libxml2-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libxml2-dev \
    libssl-dev \
    unzip

RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

RUN apt-get update && apt-get install -y libc-client-dev libkrb5-dev libsodium-dev

RUN apt install imagemagick -y

# Install php extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install gd

RUN docker-php-ext-install mysqli pdo_mysql
RUN docker-php-ext-install zip
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install opcache
RUN docker-php-ext-install intl

RUN docker-php-ext-install sodium
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install sockets
RUN docker-php-ext-install exif



# mogrify -interlace none *.png
#disabling interlace on png files

RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
        && docker-php-ext-install imap \
        && docker-php-ext-enable imap



# Config Apache2
RUN a2enmod rewrite



# Installing composer and prestissimo globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1 COMPOSER_MEMORY_LIMIT=-1
#RUN composer global require hirak/prestissimo --prefer-dist --no-progress --no-suggest --classmap-authoritative --no-plugins --no-scripts
RUN composer self-update

RUN pecl install redis && docker-php-ext-enable redis
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN pecl install apcu && docker-php-ext-enable apcu


RUN curl -fsSL https://deb.nodesource.com/setup_current.x | bash -

RUN apt install -y nodejs
#RUN npm install -g sass --save-dev
#RUN npm install -g grunt --save-dev
#RUN npm install && npm run dev

##RUN echo 'LogFormat "%v:%p %h %l %u %t %{Host}i \"%r\" %>s %O \"%{Referer}i\" \"%{User-Agent}i\" %T %D" uss_combined' >> /etc/apache2/apache2.conf


RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/ssl-cert-snakeoil.key -out /etc/ssl/certs/ssl-cert-snakeoil.pem -subj "/C=AT/ST=Vienna/L=Vienna/O=Security/OU=Development/CN=localhost"
RUN a2enmod ssl
RUN a2ensite default-ssl

# Sockets
RUN docker-php-ext-install sockets && docker-php-ext-enable sockets
