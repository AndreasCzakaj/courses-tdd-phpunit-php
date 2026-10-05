<?php

// The user self service, for PHP's built-in web server: it sends every request to this script.
// Run it with `composer uss`, see README.md.

declare(strict_types=1);

use BinaryStars\Tdd\UssDirty\LoginHandler;

require __DIR__ . '/../vendor/autoload.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $path === '/uss/login') {
    (new LoginHandler())->handle();
} else {
    http_response_code(404);
}
