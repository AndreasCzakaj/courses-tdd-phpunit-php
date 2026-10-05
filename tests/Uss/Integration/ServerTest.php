<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Uss\Integration;

use BinaryStars\Tdd\Tests\Uss\Creators;
use BinaryStars\Tdd\Uss\AccountDaoPdoImpl;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * All parts together: HTTP => PHP's built-in web server => bin/uss-server.php
 * => controller => service => SQLite.
 * The details of each scenario are covered by the (much faster) unit tests.
 */
#[RequiresPhpExtension('pdo_sqlite')]
class ServerTest extends TestCase
{
    private const PROJECT_ROOT = __DIR__ . '/../../..';

    private static string $dbPath;
    private static string $baseUrl;
    /** @var resource */
    private static $server;

    public static function setUpBeforeClass(): void
    {
        self::$dbPath = tempnam(sys_get_temp_dir(), 'uss-db-');
        $dao = new AccountDaoPdoImpl('sqlite:' . self::$dbPath);
        $dao->createTable();
        $dao->save(Creators::verifiedAccount());
        $dao->save(Creators::notVerifiedAccount());

        $address = '127.0.0.1:' . self::findFreePort();
        self::$baseUrl = 'http://' . $address;
        self::$server = proc_open(
            [PHP_BINARY, '-S', $address, 'bin/uss-server.php'],
            // the server logs each request to stderr: keep the test output clean
            [1 => ['file', self::$dbPath . '.log', 'a'], 2 => ['file', self::$dbPath . '.log', 'a']],
            $pipes,
            self::PROJECT_ROOT,
            ['USS_DB' => self::$dbPath] + getenv(),
        );
        self::waitUntilReachable($address);
    }

    public static function tearDownAfterClass(): void
    {
        proc_terminate(self::$server);
        proc_close(self::$server);
        unlink(self::$dbPath);
        unlink(self::$dbPath . '.log');
    }

    #[Test]
    #[DataProvider('errors')]
    public function postLoginErrors(array $body, int $expectedStatus): void
    {
        [$status, $json] = $this->postLogin(json_encode($body));

        self::assertSame($expectedStatus, $status);
        self::assertSame(['error'], array_keys($json));
    }

    public static function errors(): array
    {
        return [
            '400: credentials have invalid syntax' => [[], 400],
            '401: no such username' => [Creators::validCredentialsUnknownUser(), 401],
            '401: wrong password' => [
                ['password' => Creators::VALID_BUT_WRONG_PASSWORD] + Creators::validCredentials(), 401,
            ],
            '400: account not verified' => [Creators::validCredentialsNotVerifiedAccount(), 400],
        ];
    }

    #[Test]
    public function postLoginWithoutJsonShouldYield400(): void
    {
        [$status] = $this->postLogin('nonsense');

        self::assertSame(400, $status);
    }

    #[Test]
    public function postLoginShouldYield200AndTheSession(): void
    {
        [$status, $json] = $this->postLogin(json_encode(Creators::validCredentials()));

        self::assertSame(200, $status);
        self::assertSame(Creators::expectedSessionJson(), $json);
    }

    #[Test]
    public function otherRoutesShouldYield404(): void
    {
        [$status] = $this->request('GET', '/');

        self::assertSame(404, $status);
    }

    /** @return array{int, mixed} HTTP status and decoded JSON body */
    private function postLogin(string $body): array
    {
        return $this->request('POST', '/uss/login', $body);
    }

    /** @return array{int, mixed} HTTP status and decoded JSON body */
    private function request(string $method, string $path, string $body = ''): array
    {
        $context = stream_context_create(['http' => [
            'method' => $method,
            'header' => 'Content-Type: application/json',
            'content' => $body,
            // do not fail for 4xx and 5xx: we want to see the response
            'ignore_errors' => true,
        ]]);
        $response = file_get_contents(self::$baseUrl . $path, false, $context);
        // 1st header line, e.g. "HTTP/1.1 200 OK"
        $status = (int) explode(' ', $http_response_header[0])[1];

        return [$status, json_decode($response, true)];
    }

    private static function findFreePort(): int
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0');
        $port = (int) substr(strrchr(stream_socket_get_name($socket, false), ':'), 1);
        fclose($socket);

        return $port;
    }

    private static function waitUntilReachable(string $address): void
    {
        for ($attempt = 0; $attempt < 100; $attempt++) {
            $connection = @fsockopen('tcp://' . $address);
            if ($connection !== false) {
                fclose($connection);
                return;
            }
            usleep(50_000);
        }
        self::fail("The server did not start: $address");
    }
}
