FROM php:7.1-fpm

RUN apt-get update && apt-get upgrade -y && \
apt-get install -y libicu-dev git redis-tools && \
docker-php-ext-install -j2 pdo_mysql mysqli bcmath intl && \
apt-get clean && \
echo "Europe/Berlin" > /etc/timezone && \
dpkg-reconfigure -f noninteractive tzdata && \
docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ &&  \
docker-php-ext-install -j$(nproc) gd

COPY config/php.ini /usr/local/etc/php/conf.d/

WORKDIR /data

ADD ./application/ /var/www/html

EXPOSE 9000 9001
