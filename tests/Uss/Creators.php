<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Uss;

use BinaryStars\Tdd\Uss\Account;
use BinaryStars\Tdd\Uss\AccountDaoArrayImpl;
use BinaryStars\Tdd\Uss\UserSelfService;
use DateTimeImmutable;

/**
 * The "creators" return valid objects.
 * A test then breaks them in 1 place, so it tests for 1 error.
 */
class Creators
{
    public const VALID_PASSWORD = 'Correct-Horse_42';
    public const VALID_BUT_WRONG_PASSWORD = 'Battery.Staple+7';

    private static ?string $validPasswordHash = null;

    public static function validPasswordHash(): string
    {
        // hashing is slow on purpose => once for all tests, and with the lowest cost
        return self::$validPasswordHash ??= password_hash(self::VALID_PASSWORD, PASSWORD_BCRYPT, ['cost' => 4]);
    }

    public static function verifiedAccount(): Account
    {
        return new Account(
            id: '0b0e7a4c-8d2f-4c3a-9a51-6f1f3c1d2e01',
            username: 'alice_verified',
            passwordHash: self::validPasswordHash(),
            email: 'alice@example.com',
            tcAccepted: new DateTimeImmutable('2026-10-01T08:00:00+00:00'),
            status: Account::STATUS_VERIFIED,
        );
    }

    public static function notVerifiedAccount(): Account
    {
        return new Account(
            id: '0b0e7a4c-8d2f-4c3a-9a51-6f1f3c1d2e02',
            username: 'bob_not_verified',
            passwordHash: self::validPasswordHash(),
            email: 'bob@example.com',
            tcAccepted: new DateTimeImmutable('2026-10-01T08:00:00+00:00'),
            status: Account::STATUS_NEW,
        );
    }

    /** @return array{username: string, password: string} */
    public static function validCredentials(): array
    {
        return ['username' => self::verifiedAccount()->username, 'password' => self::VALID_PASSWORD];
    }

    /** @return array{username: string, password: string} */
    public static function validCredentialsNotVerifiedAccount(): array
    {
        return ['username' => self::notVerifiedAccount()->username, 'password' => self::VALID_PASSWORD];
    }

    /** @return array{username: string, password: string} */
    public static function validCredentialsUnknownUser(): array
    {
        return ['username' => 'idonotexist', 'password' => self::VALID_PASSWORD];
    }

    /** @return array{accountId: string, username: string, email: string} */
    public static function expectedSessionJson(): array
    {
        $account = self::verifiedAccount();

        return ['accountId' => $account->id, 'username' => $account->username, 'email' => $account->email];
    }

    /** A service with working dependencies: 1 verified and 1 not verified account */
    public static function userSelfServiceWithWorkingDeps(): UserSelfService
    {
        return new UserSelfService(new AccountDaoArrayImpl(self::verifiedAccount(), self::notVerifiedAccount()));
    }
}
