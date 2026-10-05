<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Uss;

use BinaryStars\Tdd\Uss\AccountDaoThrowingImpl;
use BinaryStars\Tdd\Uss\AccountNotVerifiedError;
use BinaryStars\Tdd\Uss\AuthenticationError;
use BinaryStars\Tdd\Uss\ServerError;
use BinaryStars\Tdd\Uss\UserSelfService;
use BinaryStars\Tdd\Uss\UserSession;
use BinaryStars\Tdd\Uss\ValidationError;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UserSelfServiceTest extends TestCase
{
    private UserSelfService $service;

    protected function setUp(): void
    {
        $this->service = Creators::userSelfServiceWithWorkingDeps();
    }

    #[Test]
    public function ifIPassNoCredentialsThereShouldBeAValidationError(): void
    {
        $this->expectExceptionObject(new ValidationError('username'));

        $this->service->login(null);
    }

    #[Test]
    public function ifIPassSyntacticallyInvalidCredentialsThereShouldBeAValidationError(): void
    {
        // given
        $credentials = ['password' => 'tooshort'] + Creators::validCredentials();

        // then
        $this->expectExceptionObject(new ValidationError('password'));

        // when
        $this->service->login($credentials);
    }

    #[Test]
    public function ifIDontHaveAnAccountThereShouldBeALoginError(): void
    {
        // given
        $credentials = Creators::validCredentialsUnknownUser();

        // then
        $this->expectExceptionObject(new AuthenticationError());

        // when
        $this->service->login($credentials);
    }

    #[Test]
    public function ifIDontPassTheRightPasswordThereShouldBeTheSameLoginError(): void
    {
        // given
        $credentials = ['password' => Creators::VALID_BUT_WRONG_PASSWORD] + Creators::validCredentials();

        // then
        $this->expectExceptionObject(new AuthenticationError());

        // when
        $this->service->login($credentials);
    }

    #[Test]
    public function ifMyAccountIsNotVerifiedYetThereShouldBeAnError(): void
    {
        // given
        $credentials = Creators::validCredentialsNotVerifiedAccount();

        // then
        $this->expectExceptionObject(new AccountNotVerifiedError());

        // when
        $this->service->login($credentials);
    }

    #[Test]
    public function ifMyAccountIsNotVerifiedAndThePasswordIsWrongThereShouldBeTheLoginError(): void
    {
        // given
        $credentials = ['password' => Creators::VALID_BUT_WRONG_PASSWORD]
            + Creators::validCredentialsNotVerifiedAccount();

        // then: the status of an account is none of a stranger's business
        $this->expectExceptionObject(new AuthenticationError());

        // when
        $this->service->login($credentials);
    }

    #[Test]
    public function ifTheDatabaseDoesNotWorkThenIShouldGetAnAppropriateError(): void
    {
        // given
        $this->service->accountDao = new AccountDaoThrowingImpl();
        $credentials = Creators::validCredentials();

        // then
        $this->expectExceptionObject(new ServerError('Database not available. Try later.'));

        // when
        $this->service->login($credentials);
    }

    #[Test]
    public function ifIPassValidCredentialsOfAVerifiedAccountThenIGetASession(): void
    {
        // given
        $credentials = Creators::validCredentials();
        $account = Creators::verifiedAccount();

        // when
        $actual = $this->service->login($credentials);

        // then: exactly these fields, e.g. no password hash
        self::assertEquals(
            new UserSession(accountId: $account->id, username: $account->username, email: $account->email),
            $actual,
        );
    }
}
