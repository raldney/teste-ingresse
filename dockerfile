FROM ubuntu:18.04

# Maintainer
MAINTAINER Raldney "raldney.alves@gmail.com"

ENV NGINX_VERSION 1.9.3-1~jessie
ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update && apt-get install -yq \
    apt-utils \
    curl \
    # Install git
    git \
    # Install php 7.2
    php7.2-cli \
    php7.2-json \
    php7.2-curl \
    php7.2-fpm \
    php7.2-gd \
    php7.2-ldap \
    php7.2-mbstring \
    php7.2-mysql \
    php7.2-soap \
    php7.2-sqlite3 \
    php7.2-xml \
    php7.2-zip \
    php7.2-intl \
    php-imagick \
    # Install tools
    nano \
    graphicsmagick \
    imagemagick \
    ghostscript \
    iputils-ping \
    nginx \
    locales \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
# RUN apt-get install -y php-pear php-fpm php-dev php-zip php-curl php-xmlrpc php-gd php-mysql php-mbstring php-xml libapache2-mod-php && apt-get clean

# NGINX
RUN ln -sf /dev/stdout /var/log/nginx/access.log
RUN ln -sf /dev/stderr /var/log/nginx/error.log
VOLUME ["/var/cache/nginx"]
RUN rm /etc/nginx/sites-available/default
ADD ./default /etc/nginx/sites-available/default

# COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_ALLOW_SUPERUSER 1

# BUILD
WORKDIR /var/www/html

# ADD ./index.php /etc/nginx/sites-available/default

EXPOSE 80 443 8080
CMD service php7.2-fpm start && nginx -g "daemon off;"