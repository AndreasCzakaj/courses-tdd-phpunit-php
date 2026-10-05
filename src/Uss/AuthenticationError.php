<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Uss;

/**
 * The SAME error for an unknown username and for a wrong password:
 * the response must not reveal which usernames exist.
 */
class AuthenticationError extends UserSelfServiceError
{
    public function __construct()
    {
        parent::__construct('unknown username or wrong password');
    }
}
