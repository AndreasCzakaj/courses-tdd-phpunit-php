<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Uss;

use BinaryStars\Tdd\Uss\AccountNotVerifiedError;
use BinaryStars\Tdd\Uss\AuthenticationError;
use BinaryStars\Tdd\Uss\ControllerUtils;
use BinaryStars\Tdd\Uss\DaoError;
use BinaryStars\Tdd\Uss\ServerError;
use BinaryStars\Tdd\Uss\ValidationError;
use DivisionByZeroError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Throwable;

class ControllerUtilsTest extends TestCase
{
    #[Test]
    #[DataProvider('errors')]
    public function shouldMapToHttpStatus(Throwable $error, int $expectedStatus): void
    {
        self::assertSame($expectedStatus, ControllerUtils::calcHttpErrorCode($error));
    }

    #[Test]
    #[DataProvider('errors')]
    public function shouldMapToMessage(Throwable $error, int $expectedStatus, string $expectedMessage): void
    {
        self::assertSame($expectedMessage, ControllerUtils::calcErrorMessage($error));
    }

    public static function errors(): array
    {
        $hidden = ControllerUtils::MESSAGE_SERVER_ERROR;

        return [
            "invalid input is the client's fault" => [new ValidationError('username'), 400, 'invalid: username'],
            'unknown username or wrong password' => [
                new AuthenticationError(), 401, 'unknown username or wrong password',
            ],
            'account not verified' => [new AccountNotVerifiedError(), 400, 'account not verified'],
            'a server error is our fault, hides the internals' => [new ServerError('db is down'), 500, $hidden],
            'any other error is our fault, hides the internals' => [new DaoError('PDO Error'), 500, $hidden],
            'anything else is our fault, hides the internals' => [new DivisionByZeroError('oops'), 500, $hidden],
        ];
    }
}
