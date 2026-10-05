<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Uss;

use BinaryStars\Tdd\Uss\AccountDaoThrowingImpl;
use BinaryStars\Tdd\Uss\ControllerUtils;
use BinaryStars\Tdd\Uss\HttpResult;
use BinaryStars\Tdd\Uss\LoginController;
use BinaryStars\Tdd\Uss\UserSelfService;
use BinaryStars\Tdd\Uss\UserSession;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class LoginControllerTest extends TestCase
{
    private const MESSAGE_LOGIN_ERROR = 'unknown username or wrong password';

    private UserSelfService $service;
    private LoginController $ctrl;
    private string $logFile;
    private string $originalErrorLog;

    protected function setUp(): void
    {
        $this->service = Creators::userSelfServiceWithWorkingDeps();
        $this->ctrl = new LoginController($this->service);

        // server errors are logged: send the log to a file instead of the console
        $this->logFile = tempnam(sys_get_temp_dir(), 'uss-log-');
        $this->originalErrorLog = (string) ini_set('error_log', $this->logFile);
    }

    protected function tearDown(): void
    {
        ini_set('error_log', $this->originalErrorLog);
        unlink($this->logFile);
    }

    #[Test]
    #[DataProvider('clientErrorCases')]
    public function clientErrors(mixed $body, int $status, string $error): void
    {
        self::assertEquals(new HttpResult($status, ['error' => $error]), $this->ctrl->action($body));
    }

    public static function clientErrorCases(): array
    {
        $valid = Creators::validCredentials();

        return [
            '400: no body' => [null, 400, 'invalid: username'],
            '400: invalid username' => [['username' => 'al'] + $valid, 400, 'invalid: username'],
            '400: invalid password' => [['password' => 'pwd'] + $valid, 400, 'invalid: password'],
            '401: no such username' => [Creators::validCredentialsUnknownUser(), 401, self::MESSAGE_LOGIN_ERROR],
            '401: wrong password' => [
                ['password' => Creators::VALID_BUT_WRONG_PASSWORD] + $valid, 401, self::MESSAGE_LOGIN_ERROR,
            ],
            '400: account not verified' => [
                Creators::validCredentialsNotVerifiedAccount(), 400, 'account not verified',
            ],
        ];
    }

    #[Test]
    public function databaseNotAvailableShouldYield500(): void
    {
        // given
        $this->service->accountDao = new AccountDaoThrowingImpl();

        // when
        $actual = $this->ctrl->action(Creators::validCredentials());

        // then: the client gets no details, the log does
        self::assertEquals(new HttpResult(500, ['error' => ControllerUtils::MESSAGE_SERVER_ERROR]), $actual);
        self::assertStringContainsString('Database not available', file_get_contents($this->logFile));
    }

    #[Test]
    public function validCredentialsShouldYield200AndTheSession(): void
    {
        // given
        $account = Creators::verifiedAccount();

        // when
        $actual = $this->ctrl->action(Creators::validCredentials());

        // then
        self::assertEquals(
            new HttpResult(200, new UserSession($account->id, $account->username, $account->email)),
            $actual,
        );
        self::assertJsonStringEqualsJsonString(
            json_encode(Creators::expectedSessionJson()),
            json_encode($actual->body),
        );
    }
}
