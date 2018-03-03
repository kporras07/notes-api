FROM kporras07/docker-php:v1.2

# Install mhsendmail.
RUN apk update && apk add \
     go \
     git
RUN mkdir /root/go
ENV GOPATH=/root/go
ENV PATH=$PATH:$GOPATH/bin
RUN go get github.com/mailhog/mhsendmail
RUN cp /root/go/bin/mhsendmail /usr/bin/mhsendmail
COPY ./php.ini /usr/local/etc/php/conf.d/docker-php.ini
RUN set -ex \
  && apk --no-cache add \
    postgresql-dev
RUN docker-php-ext-configure pdo_pgsql
RUN docker-php-ext-install pdo_pgsql soap
# Cleanup
RUN rm -rf /tmp/* \
    && rm -rf /var/cache/apk/* \
    && rm -rf tmp/*
