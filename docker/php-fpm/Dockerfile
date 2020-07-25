FROM php:7.4-fpm

ARG environment=prod

ADD https://github.com/Yelp/dumb-init/releases/download/v1.2.0/dumb-init_1.2.0_amd64 /usr/local/bin/dumb-init
RUN chmod +x /usr/local/bin/dumb-init

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    libgmp-dev \
    build-essential \
    apt-utils \
    libtool \
    autoconf \
    pkg-config \
    uuid-dev \
    libsodium-dev \
    wget \
    bzip2 \
    git \
    tar \
    zlib1g-dev \
    libzip-dev \
    libxml2-dev \
    librabbitmq-dev \
    ssh-client \
    libpq-dev

RUN docker-php-ext-install pdo_pgsql \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install gmp \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install zip \
    && docker-php-ext-install sockets \
    && rm -rf /tmp/pear

RUN mkdir ~/.ssh
RUN echo 'Host github.com\n\
            StrictHostKeyChecking no\n\
            IdentityFile ~/.ssh-local/id_rsa' > ~/.ssh/config
RUN echo 'Host bitbucket.org\n\
            StrictHostKeyChecking no\n\
            IdentityFile ~/.ssh-local/id_rsa' >> ~/.ssh/config

RUN rm -f /usr/local/etc/www.conf.default && rm -f /usr/local/etc/docker.conf && rm -f /usr/local/etc/zz-docker.conf

RUN if [ $environment = "dev" ]; then pecl install xdebug && docker-php-ext-enable xdebug; fi

RUN pecl install amqp && docker-php-ext-enable amqp

COPY docker/php-fpm/config/php_${environment}.ini /usr/local/etc/php/php.ini
COPY docker/php-fpm/config/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY docker/php-fpm/config/www_${environment}.conf /usr/local/etc/php-fpm.d/www.conf

WORKDIR /usr/src/app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /usr/src/app


RUN if [ $environment = "prod" ]; then APP_ENV=prod \
     && echo "Fix permissions" \
     && chown -R www-data:www-data /usr/src/app; fi

RUN if [ $environment = "dev" ]; then composer install --no-interaction; fi

CMD ["php-fpm"]
