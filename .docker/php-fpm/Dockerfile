FROM php:8.3.2-fpm-alpine

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

RUN apk add --no-cache \
    oniguruma-dev \
    postgresql-dev \
    nodejs \
    npm \
    supervisor

RUN mkdir -p /var/www

WORKDIR /var/www

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# MacOS staff group's gid is 20, so is the dialout group in alpine linux. We're not using it, let's just remove it.
RUN delgroup dialout

RUN addgroup -g ${GID} --system melnichukme
RUN adduser -G melnichukme --system -D -s /bin/sh -u ${UID} melnichukme

RUN sed -i "s/user = www-data/user = melnichukme/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = melnichukme/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

RUN docker-php-ext-install \
      mbstring \
      pdo \
      pdo_mysql

RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis pdo_pgsql

COPY supervisord.conf /etc/supervisord.conf
RUN mkdir -p /var/log/supervisor

USER melnichukme

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]

#CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]