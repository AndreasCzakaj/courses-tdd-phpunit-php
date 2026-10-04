# The image of the "app": prints a new UUID
#   docker build -f Dockerfile.app -t tdd-phpunit-php-app .
#   docker run --rm tdd-phpunit-php-app
# (`Dockerfile` w/out suffix is the development image: PHP + Composer + pcov)

# stage 1: install the production dependencies, w/out PHPUnit etc.
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-progress
COPY src/ src/
RUN composer dump-autoload --no-dev --optimize

# stage 2: the runtime image, production code only
FROM php:8.3-cli-alpine
WORKDIR /app
COPY --from=vendor /app/vendor/ vendor/
COPY src/ src/
COPY resources/ resources/
COPY bin/uuid.php bin/
USER nobody
ENTRYPOINT ["php", "bin/uuid.php"]
