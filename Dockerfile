FROM php:8.0-fpm


WORKDIR /var/www/bdm


COPY . /var/www/bdm


ADD ./docker/sources.txt /apt/sources.list


COPY --from=composer /usr/bin/composer /usr/bin/composer


# Workaround https://unix.stackexchange.com/questions/2544/how-to-work-around-release-file-expired-problem-on-a-local-mirror

RUN echo "Acquire::Check-Valid-Until \"false\";\nAcquire::Check-Date \"false\";" | cat > /etc/apt/apt.conf.d/10no--check-valid-until


RUN apt update \

    && apt install -y nano make git libssl-dev zlib1g-dev curl git unzip netcat libxml2-dev libpq-dev libzip-dev \

    && pecl install apcu \

    && pecl install xdebug-3.1.1 \

    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \

    && docker-php-ext-install -j$(nproc) zip opcache intl pdo_pgsql pgsql \

    && docker-php-ext-enable apcu intl xdebug pdo_pgsql \

    && docker-php-ext-enable apcu pdo_pgsql sodium \

    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*


EXPOSE 9000



