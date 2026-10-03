# TDD with PHPUnit

Branch `main` contains the exercises, branch `solution` contains the solutions.

# Requirements

* PHP >= 8.2 with extensions `dom`, `xml`, `xmlwriter`, `mbstring`, `json`
  * Debian/Ubuntu: `sudo apt install php-cli php-xml php-mbstring`
* [Composer](https://getcomposer.org/)
* optional, for coverage: `pcov` or `xdebug`
* ... or just Docker, see below

# Initially, after cloning

``` Bash
composer install
```

# Daily use

## Run tests once

``` Bash
composer test                   # same as: vendor/bin/phpunit
```

## Run tests once, as "executable specification"

``` Bash
composer test:dox               # same as: vendor/bin/phpunit --testdox
```

## Run tests once, with coverage

``` Bash
composer test:coverage
```

## Run a subset

``` Bash
vendor/bin/phpunit tests/HelloTest.php              # one file
vendor/bin/phpunit tests/Matchers                   # one folder
vendor/bin/phpunit --filter shouldStartWith         # by name
```

## Run tests continuously ("watch mode"), TDD style

PHPUnit has no watch mode. Options:

* PhpStorm: "Rerun Automatically" in the test runner window
* Linux/macOS: `find src tests | entr -c vendor/bin/phpunit`

# Without local PHP: Docker

``` Bash
docker build -t tdd-phpunit-php .
docker run --rm -it -v "$PWD":/app tdd-phpunit-php composer install
docker run --rm -it -v "$PWD":/app tdd-phpunit-php composer test
```

On Linux, add `-u "$(id -u):$(id -g)"` so that the files created in the container
(`vendor/`, `.phpunit.cache/`) belong to you and not to `root`:

``` Bash
docker run --rm -it -u "$(id -u):$(id -g)" -v "$PWD":/app tdd-phpunit-php composer test
```

# Folder structure

```
src/                    production code, namespace BinaryStars\Tdd
  Hello.php
  Matchers/
  Fibonacci/
  FunWithFlags/           "Fun With Flags": decorator pattern
tests/                  test code, namespace BinaryStars\Tdd\Tests, files must be named *Test.php
  HelloTest.php
  Matchers/
  Fibonacci/
  FunWithFlags/
resources/              test data
composer.json           dependencies, autoloading (PSR-4), scripts
phpunit.xml             PHPUnit configuration
```
