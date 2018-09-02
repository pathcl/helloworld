FROM richarvey/nginx-php-fpm:1.3.5
MAINTAINER Henri LARGET<henri.larget@aboutgoods.net>
RUN set -ex \
  && apk --no-cache add \
    postgresql-dev
RUN docker-php-ext-install pdo_pgsql
RUN pecl bundle -d /usr/src/php/ext redis \
  && rm /usr/src/php/ext/redis-*.tgz \
  && docker-php-ext-install redis
ADD wwwroot /var/www/html/
CMD ["/start.sh"]
