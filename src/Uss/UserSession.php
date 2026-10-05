<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Uss;

final readonly class UserSession
{
    public function __construct(
        public string $accountId,
        public string $username,
        public string $email,
    ) {
    }
}
