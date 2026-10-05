<?php

// The user self service, for PHP's built-in web server: it sends every request to this script.
// Run it with `composer uss`, see README.md.
//
// Integration code only: wires the parts, defines the route, sends the response.
// No logic here => nothing to unit test, the integration test covers it.

declare(strict_types=1);

use BinaryStars\Tdd\Uss\AccountDaoPdoImpl;
use BinaryStars\Tdd\Uss\HttpResult;
use BinaryStars\Tdd\Uss\LoginController;
use BinaryStars\Tdd\Uss\UserSelfService;

require __DIR__ . '/../vendor/autoload.php';

$accountDao = new AccountDaoPdoImpl(require __DIR__ . '/uss-config.php');
$service = new UserSelfService($accountDao);
$loginController = new LoginController($service);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$body = json_decode(file_get_contents('php://input'), true);

$result = $_SERVER['REQUEST_METHOD'] === 'POST' && $path === '/uss/login'
    ? $loginController->action($body)
    : new HttpResult(404, ['error' => 'not found']);

http_response_code($result->status);
header('Content-Type: application/json');
echo json_encode($result->body);
