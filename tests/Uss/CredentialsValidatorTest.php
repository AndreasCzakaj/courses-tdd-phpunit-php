<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Uss;

use BinaryStars\Tdd\Uss\Credentials;
use BinaryStars\Tdd\Uss\CredentialsValidator;
use BinaryStars\Tdd\Uss\ValidationError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CredentialsValidatorTest extends TestCase
{
    #[Test]
    #[DataProvider('invalidUsernames')]
    public function validateUsernameShouldFail(mixed $given): void
    {
        $this->expectExceptionObject(new ValidationError('username'));

        CredentialsValidator::validateUsername($given);
    }

    public static function invalidUsernames(): array
    {
        return [
            'null' => [null],
            'empty' => [''],
            'empty whitespace' => ['        '],
            'not a string' => [12345678],
            'an array (injection)' => [['$ne' => '']],
            'too short' => ['1234567'],
            'too short with padding' => ['1234567 '],
            'too long' => ['123456789012345678901'],
            'invalid char: blank' => ['1234 6789'],
            'invalid char: allowed in passwords only' => ['1234.6789'],
            'trailing newline' => ["12345678\n"],
        ];
    }

    #[Test]
    #[DataProvider('validUsernames')]
    public function validateUsernameShouldPass(string $given): void
    {
        self::assertSame($given, CredentialsValidator::validateUsername($given));
    }

    public static function validUsernames(): array
    {
        return [
            'min size' => ['12345678'],
            'max size' => ['12345678901234567890'],
            'all allowed chars' => ['aZ09-_aZ'],
            'validCredentials' => [Creators::validCredentials()['username']],
            'validCredentialsUnknownUser' => [Creators::validCredentialsUnknownUser()['username']],
        ];
    }

    #[Test]
    #[DataProvider('invalidPasswords')]
    public function validatePasswordShouldFail(mixed $given): void
    {
        $this->expectExceptionObject(new ValidationError('password'));

        CredentialsValidator::validatePassword($given);
    }

    public static function invalidPasswords(): array
    {
        return [
            'null' => [null],
            'empty' => [''],
            'empty whitespace' => ['            '],
            'not a string' => [123456789012],
            'too short' => ['12345678901'],
            'too short with padding' => ['12345678901 '],
            'too long' => ['123456789012345678901234567890123'],
            'invalid char: blank' => ['123456 89012'],
            'invalid char: !' => ['123456!89012'],
            'trailing newline' => ["123456789012\n"],
        ];
    }

    #[Test]
    #[DataProvider('validPasswords')]
    public function validatePasswordShouldPass(string $given): void
    {
        self::assertSame($given, CredentialsValidator::validatePassword($given));
    }

    public static function validPasswords(): array
    {
        return [
            'min size' => ['123456789012'],
            'max size' => ['12345678901234567890123456789012'],
            'all allowed chars' => ['aZ09-_.,+aZ0'],
            'VALID_PASSWORD' => [Creators::VALID_PASSWORD],
            'VALID_BUT_WRONG_PASSWORD' => [Creators::VALID_BUT_WRONG_PASSWORD],
        ];
    }

    #[Test]
    #[DataProvider('invalidCredentials')]
    public function validateCredentialsShouldFail(mixed $given, string $field): void
    {
        $this->expectExceptionObject(new ValidationError($field));

        CredentialsValidator::validateCredentials($given);
    }

    public static function invalidCredentials(): array
    {
        return [
            'null' => [null, 'username'],
            'empty array' => [[], 'username'],
            'not an array' => ['alice_verified', 'username'],
            'invalid username' => [['username' => 'al'] + Creators::validCredentials(), 'username'],
            'invalid password' => [['password' => 'pwd'] + Creators::validCredentials(), 'password'],
            'both invalid: reports the 1st one' => [['username' => 'al', 'password' => 'pwd'], 'username'],
        ];
    }

    #[Test]
    #[DataProvider('validCredentials')]
    public function validateCredentialsShouldPass(array $given): void
    {
        self::assertEquals(
            new Credentials($given['username'], $given['password']),
            CredentialsValidator::validateCredentials($given),
        );
    }

    public static function validCredentials(): array
    {
        return [
            'validCredentials' => [Creators::validCredentials()],
            'validCredentialsNotVerifiedAccount' => [Creators::validCredentialsNotVerifiedAccount()],
            'validCredentialsUnknownUser' => [Creators::validCredentialsUnknownUser()],
        ];
    }

    #[Test]
    public function theErrorShouldNameTheInvalidField(): void
    {
        $error = new ValidationError('username');

        self::assertSame('username', $error->field);
        self::assertSame('invalid: username', $error->getMessage());
    }
}
