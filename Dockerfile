FROM php:8.3-cli
RUN apt-get update && apt-get install -y --no-install-recommends git unzip \
    && rm -rf /var/lib/apt/lists/* \
    && pecl install pcov && docker-php-ext-enable pcov
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
# the project is mounted from the host, i.e. owned by "somebody else"
# => tell git that /app is fine, and give composer a home that any user can write to
RUN git config --system --add safe.directory /app
ENV COMPOSER_HOME=/tmp/composer
WORKDIR /app
