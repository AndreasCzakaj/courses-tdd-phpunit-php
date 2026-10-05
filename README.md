# TDD with PHPUnit

Branch `main` contains the exercises, branch `solution` contains the solutions.

# Requirements

* PHP >= 8.2 with extensions `dom`, `xml`, `xmlwriter`, `mbstring`, `json`
  * Debian/Ubuntu: `sudo apt install php-cli php-xml php-mbstring`
* [Composer](https://getcomposer.org/)
* optional, for coverage: `pcov` or `xdebug`
* for the "User Self Service" task: extension `pdo_sqlite`
  * Debian/Ubuntu: `sudo apt install php-sqlite3`
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

## Lint: coding standard PSR-12, cyclomatic complexity

``` Bash
composer lint                   # same as: vendor/bin/phpcs
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

For watch mode:
``` Bash
docker run --rm -it -v "$PWD":/app tdd-phpunit-php php vendor/bin/phpunit-watcher watch
```

On Linux, add `-u "$(id -u):$(id -g)"` so that the files created in the container
(`vendor/`, `.phpunit.cache/`) belong to you and not to `root`:

``` Bash
docker run --rm -it -u "$(id -u):$(id -g)" -v "$PWD":/app tdd-phpunit-php composer test
```

# CI/CD

`.gitlab-ci.yml` defines the GitLab pipeline: lint => test => package

* **lint**: `composer lint`, any violation breaks the build (rules: `phpcs.xml`)
* **test**: `composer test:ci`, all tests with coverage, less than 90% breaks the build
* **package**: builds the Docker image of the "app" (`Dockerfile.app`), which prints a UUID

On branch `main`, the pipeline is RED by design: the linter rejects the legacy code in
`src/UssDirty` (complexity), and the first test must fail.
On branch `solution` it is GREEN.

Run the steps locally:

``` Bash
composer lint
composer test:ci
docker build -f Dockerfile.app -t tdd-phpunit-php-app .
docker run --rm tdd-phpunit-php-app
```

# User Self Service: Login (`src/UssDirty`)

`src/UssDirty/LoginHandler.php` implements the login. It works, but everything happens in
the handler of the route `/uss/login`: validation, database calls, error handling.
There are no tests.

Scenarios:

* Credentials (username + password) have invalid syntax => 400
  * username: min 8, max 20 chars; `[a-zA-Z0-9\-_]`
  * password: min 12, max 32 chars; `[a-zA-Z0-9\-_.,+]`
* Credentials have valid syntax, but no such username => 401
* Username exists, but wrong password => SAME(!) error 401
* Credentials OK, but account status is not "verified" => 400
* All OK => 200 + session object including account ID, username, email

## Your task

* Extract functions and classes
* Separate Operation code (logic: validation, password check, decisions) from
  Integration code (HTTP, database)
* Add tests along the way
* Done when: all scenarios are covered by tests, and `composer lint` is green
  (now: "Function's cyclomatic complexity (14) exceeds allowed maximum of 5")

## Run it

``` Bash
composer uss                    # SQLite database + some accounts, then PHP's built-in web server
```

`composer uss` runs 2 commands. Run them yourself if port 3000 is taken:

``` Bash
php bin/uss-seed.php                            # creates the database, see the console output
php -S localhost:3055 bin/uss-server.php        # the route is defined in bin/uss-server.php
```

With Docker:

``` Bash
docker run --rm -it -p 3000:3000 -v "$PWD":/app tdd-phpunit-php \
  sh -c "php bin/uss-seed.php && php -S 0.0.0.0:3000 bin/uss-server.php"
```

``` Bash
curl -i -X POST localhost:3000/uss/login \
  -H 'Content-Type: application/json' \
  -d '{"username": "alice_verified", "password": "Correct-Horse_42"}'
```

# Folder structure

```
src/                    production code, namespace BinaryStars\Tdd
  Hello.php
  Matchers/
  Fibonacci/
  FunWithFlags/           "Fun With Flags": decorator pattern
  UssDirty/               "User Self Service: Login": legacy code task
tests/                  test code, namespace BinaryStars\Tdd\Tests, files must be named *Test.php
  HelloTest.php
  Matchers/
  Fibonacci/
  FunWithFlags/
resources/              test data
composer.json           dependencies, autoloading (PSR-4), scripts
phpunit.xml             PHPUnit configuration
phpcs.xml               linter configuration
bin/                    the "app" (uuid.php), CI helper, user self service (uss-*.php)
.gitlab-ci.yml          CI/CD pipeline
Dockerfile              development image: PHP + Composer + pcov
Dockerfile.app          Docker image of the "app"
```
