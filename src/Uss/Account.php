<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Uss;

use DateTimeImmutable;

final readonly class Account
{
    public const STATUS_NEW = 'new';
    public const STATUS_VERIFIED = 'verified';

    public function __construct(
        public string $id,
        public string $username,
        // created by password_hash()
        public string $passwordHash,
        public string $email,
        public DateTimeImmutable $tcAccepted,
        public string $status,
    ) {
    }
}
