# boxedcode/alpine-nginx-php-fpm

FROM alpine:3.5
MAINTAINER boxedcode <hello@boxedcode.com>

# Set PHP version (allow it to be overridden at build time)
ARG PHP_VER="7.1.14"

# Environment variables
ENV BUILD_PACKAGES="wget tar make gcc g++ zlib-dev libressl-dev pcre-dev fcgi-dev jpeg-dev libmcrypt-dev bzip2-dev curl-dev libpng-dev libxslt-dev postgresql-dev perl-dev file acl-dev libedit-dev" \
    ESSENTIAL_PACKAGES="nginx libressl pcre zlib supervisor sed re2c m4 ca-certificates py-pip" \
    UTILITY_PACKAGES="bash vim" \
    PHP_VER=${PHP_VER} \
    GETTEXT_VER="0.19.8.1"

# Configure essential and utility packages
RUN apk update && \
    apk --no-cache --progress add $ESSENTIAL_PACKAGES $UTILITY_PACKAGES && \
    mkdir -p /run/nginx/ && \
    chmod ugo+w /run/nginx/ && \
    rm -f /etc/nginx/nginx.conf && \
    mkdir -p /etc/nginx/conf.d && \
    mkdir -p /etc/nginx/ssl/ && \
    mkdir -p /var/www/html/ && \
    chmod -R 775 /var/www/ && \
    chown -R nginx:nginx /var/www/ && \
    pip install --upgrade pip && \
    pip install supervisor-stdout


# Build and configure php/php-fpm
RUN apk --no-cache --progress add $BUILD_PACKAGES && \
    wget http://ftp.gnu.org/pub/gnu/gettext/gettext-${GETTEXT_VER}.tar.gz && \
    tar -zxvf gettext-${GETTEXT_VER}.tar.gz && \
    cd gettext-${GETTEXT_VER} && \
    ./configure && \
    make && \
    make install && \
    make clean && \
    cd .. && \
    rm -f gettext-${GETTEXT_VER}.tar.gz && \
    rm -rf gettext-${GETTEXT_VER} && \
    wget http://ftp.gnu.org/gnu/bison/bison-3.0.4.tar.gz && \
    tar -zxvf bison-3.0.4.tar.gz && \
    cd bison-3.0.4 && \
    ./configure && \
    make && \
    make install && \
    make clean && \
    cd .. && \
    rm -f bison-3.0.4.tar.gz && \
    rm -rf bison-3.0.4 && \
    wget http://de2.php.net/get/php-${PHP_VER}.tar.gz/from/this/mirror -O php-${PHP_VER}.tar.gz && \
    tar -zxvf php-${PHP_VER}.tar.gz && \
    cd php-${PHP_VER} && \
    ./configure \
    --prefix=/usr \
    --with-config-file-path=/etc \
    --with-config-file-scan-dir=/etc/php.d \
    --disable-cgi \
    --enable-mbstring \
    --enable-mysqlnd \
    --enable-soap \
    --enable-calendar \
    --enable-inline-optimization \
    --enable-sockets \
    --enable-sysvsem \
    --enable-sysvshm \
    --enable-pcntl \
    --enable-mbregex \
    --enable-exif \
    --enable-bcmath \
    --enable-zip \
    --enable-ftp \
    --enable-opcache \
    --enable-fpm \
    --enable-gd-native-ttf \
    --with-pdo-pgsql \
    --with-libedit \
    --with-libxml-dir=/usr \
    --with-curl \
    --with-mcrypt \
    --with-zlib \
    --with-gd \
    --with-pgsql \
    --with-bz2 \
    --with-zlib \
    --with-mhash \
    --with-pcre-regex \
    --with-pdo-mysql \
    --with-jpeg-dir=/usr \
    --with-png-dir=/usr \
    --with-openssl \
    --with-fpm-user=nginx \
    --with-fpm-group=nginx \
    --with-libdir=/usr/lib \
    --with-gettext \
    --with-xmlrpc \
    --with-xsl \
    --with-pear \
    --with-mysqli && \
    make && \
    make install && \
    make clean && \
    cd .. && \
    rm -f php-${PHP_VER}.tar.gz && \
    rm -rf php-${PHP_VER} && \
    mkdir -p /etc/php.d && \
    chmod 755 /etc/php.d && \
    mkdir -p /usr/lib/php/modules && \
    ln -s /usr/lib/php/extensions/no-debug-non-zts-20160303/opcache.so /usr/lib/php/modules/opcache.so

# Copy manifest folder
COPY ./manifest/ /
RUN chmod 755 /entrypoint.sh
RUN chmod 777 -R /var/www/html

ADD ./application/ /var/www/html

WORKDIR /var/www/html

# Setup Volume
#VOLUME ["/var/www/html"]
#VOLUME ["/etc/nginx/ssl"]
#VOLUME ["/var/log/nginx"]

# Expose Ports
EXPOSE 8080

CMD ["/bin/bash", "/entrypoint.sh"]
