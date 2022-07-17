FROM php:8.1-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    autoconf \
    g++ \
    git \
    libzip-dev \
    unzip \
    libtool \
    make \
    libonig-dev \ 
    && docker-php-ext-install mbstring \
    && docker-php-ext-install sockets \
    && docker-php-ext-install zip \
    && touch /usr/local/etc/php/bogus.ini \
	&& pear config-set php_ini /usr/local/etc/php/bogus.ini \
	&& pecl config-set php_ini /usr/local/etc/php/bogus.ini \
    && apt-get update \ 
    && apt-get install -y --no-install-recommends \ 
    libevent-dev \
    openssl \
    libssl-dev \
    && pecl install event \
	&& docker-php-ext-enable event \
	&& mv /usr/local/etc/php/conf.d/docker-php-ext-event.ini \
		/usr/local/etc/php/conf.d/docker-php-ext-zz-event.ini \
	&& rm /usr/local/etc/php/bogus.ini 

RUN  docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install pcntl

RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql


# Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/bin \
    --filename=composer \
  && composer self-update
