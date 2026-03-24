FROM ubuntu:noble

ARG DEBIAN_FRONTEND=noninteractive
ARG ACCEPT_EULA=Y

# Утановка системных пакетов и настройка системы
RUN apt-get update \
    && apt-get install -y \
        gnupg curl ca-certificates unzip locales git  \
    	apt-transport-https software-properties-common\
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen

RUN add-apt-repository ppa:ondrej/php -y

RUN apt-get update  \
    && apt-get upgrade -y  \
    && apt-get install -y php8.3-fpm php8.3-cli php8.3-mbstring php8.3-zip nodejs npm --no-install-recommends

# Composer install
RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin \
    && echo "alias composer='composer'" >> /root/.bashrc \
    && rm -rf ~/.composer

EXPOSE 9000
CMD ["php-fpm8.3"]
