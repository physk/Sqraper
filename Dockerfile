FROM alpine:latest

RUN echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories && \
    apk update && apk upgrade && \
    apk add --no-cache \
        ca-certificates \
        shadow \
        bash \
        php7 \
        php7-fpm \
        php7-mysqli \
        php7-json \
        php7-openssl \
        php7-curl \
        php7-zlib \
        php7-xml \
        php7-phar \
        php7-dom \
        php7-xmlreader \
        php7-ctype \
        php7-mbstring \
        php7-gd \
        curl \
        nginx \
        tor \
        torsocks

# InstalL s6 overlay
RUN wget https://github.com/just-containers/s6-overlay/releases/download/v1.21.4.0/s6-overlay-amd64.tar.gz -O s6-overlay.tar.gz && \
    tar xfv s6-overlay.tar.gz -C / && \
    rm -r s6-overlay.tar.gz

# Add user
RUN addgroup -g 911 abc && \
    adduser -u 911 -D -G abc abc

COPY docker/root /

COPY docker/config/nginx.conf /etc/nginx/nginx.conf
COPY docker/config/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY docker/config/php.ini /etc/php7/conf.d/zzz_custom.ini

RUN mkdir /app && \
    chown -hR 911:911 /app

COPY seeddata.php /app/seeddata.php
COPY sqraper.php /app/sqraper.php
COPY sqraperextra.php /app/sqraperextra.php
COPY search_replace.json /app/search_replace.json
COPY docker/config/sqraper_config.json /app/sqraper_config.json

RUN cp /etc/tor/torrc.sample /etc/tor/torrc && \
    sed -i 's/# ControlPort 9051/ControlPort 9051/g' /etc/tor/torrc && \
    sed -i 's/# CookieAuthentication 1/CookieAuthentication 0/g' /etc/tor/torrc


HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping

VOLUME [ "/var/www" ]
ENTRYPOINT ["/init"]
