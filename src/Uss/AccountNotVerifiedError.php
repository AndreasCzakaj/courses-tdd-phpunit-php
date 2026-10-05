<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Uss;

class AccountNotVerifiedError extends UserSelfServiceError
{
    public function __construct()
    {
        parent::__construct('account not verified');
    }
}
