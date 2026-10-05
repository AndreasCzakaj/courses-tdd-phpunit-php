<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Uss;

/**
 * The validation functions return the valid value, so the caller gets it with the right type.
 */
class CredentialsValidator
{
    // `\z`: with `$`, a trailing newline would be accepted
    private const REGEX_USERNAME = '/^[a-zA-Z0-9\-_]{8,20}\z/';
    private const REGEX_PASSWORD = '/^[a-zA-Z0-9\-_.,+]{12,32}\z/';

    public static function validateUsername(mixed $username): string
    {
        return self::validate($username, self::REGEX_USERNAME, 'username');
    }

    public static function validatePassword(mixed $password): string
    {
        return self::validate($password, self::REGEX_PASSWORD, 'password');
    }

    public static function validateCredentials(mixed $credentials): Credentials
    {
        // the input is untrusted: it may be anything, e.g. the body of an HTTP request
        $data = is_array($credentials) ? $credentials : [];

        return new Credentials(
            username: self::validateUsername($data['username'] ?? null),
            password: self::validatePassword($data['password'] ?? null),
        );
    }

    private static function validate(mixed $value, string $regex, string $field): string
    {
        if (is_string($value) && preg_match($regex, $value) === 1) {
            return $value;
        }
        throw new ValidationError($field);
    }
}
