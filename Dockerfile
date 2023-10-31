FROM php:latest
LABEL maintainer="ChengYao" version="1.0" license="Apache-2.0" app.name="nextphp"

##
# ---------- env settings ----------
##
# --build-arg timezone=Asia/Shanghai
ARG timezone

ENV TIMEZONE=${timezone:-"Asia/Shanghai"}

# update
RUN set -ex \
    # show php version and extensions
    && php -v \
    && php -m \
    && pecl install -D 'enable-sockets="yes" enable-openssl="yes" enable-http2="yes" enable-mysqlnd="yes" enable-swoole-json="no" enable-swoole-curl="yes" enable-cares="no"' swoole \
    && echo -e "\033[42;37m Build Completed :).\033[0m\n"

WORKDIR /www

# Composer Cache
# COPY ./composer.* /opt/www/
# RUN composer install --no-dev --no-scripts

COPY . /www
RUN composer install --no-dev -o && php bin/swoole.php

EXPOSE 8989

ENTRYPOINT ["php", "/www/bin/swoole.php"]
