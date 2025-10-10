FROM dunglas/frankenphp:latest-php8.3 AS builder

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -

RUN apt-get update && apt-get install -y \
    libnss3-tools \
    cron \
    default-mysql-client \
    git \
    gnupg \
    gzip \
    libbz2-dev \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libmagickwand-dev \
    libmcrypt-dev \
    libonig-dev \
    libpng-dev \
    libsodium-dev \
    libssh2-1-dev \
    libwebp-dev \
    libxslt1-dev \
    libzip-dev \
    lsof \
    mailutils \
    msmtp \
    nodejs \
    procps \
    vim \
    zip \
  && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN pecl channel-update pecl.php.net && pecl install \
    imagick \
    redis \
    ssh2-1.3.1 \
    xdebug \
  && pecl clear-cache \
  && rm -rf /tmp/pear

RUN install-php-extensions \
    bcmath \
    bz2 \
    calendar \
    exif \
    gd \
    gettext \
    intl \
    mbstring \
    mysqli \
    opcache \
    pcntl \
    pdo_mysql \
    soap \
    sockets \
    sodium \
    sysvmsg \
    sysvsem \
    sysvshm \
    xsl \
    zip

RUN curl -sS https://getcomposer.org/installer | \
  php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-enable \
    imagick \
    redis \
    ssh2


#RUN docker-php-ext-enable xdebug

ARG DOCKER_USER=franken
ARG USER_ID
ARG GROUP_ID
ENV DOCKER_USER=$DOCKER_USER
ENV GROUP_ID=$GROUP_ID
ENV USER_ID=$USER_ID

RUN groupadd $DOCKER_USER -g $USER_ID && useradd $DOCKER_USER -u $GROUP_ID -g $DOCKER_USER
RUN chown -R $DOCKER_USER /data/caddy


USER $DOCKER_USER
VOLUME /var/www
WORKDIR /var/www

CMD ["frankenphp", "run", "--config", "/config/Caddyfile", "index.php"]
