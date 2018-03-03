FROM kporras07/docker-drupal-cli:v0.10.6
RUN \
    DEBIAN_FRONTEND=noninteractive apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get -y --force-yes install \
    php7.1-dev \
    php7.1-soap \
    php7.1-mbstring \
    php7.1-zip \
    php7.1-pdo-pgsql
