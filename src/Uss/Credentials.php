<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Uss;

final readonly class Credentials
{
    public function __construct(
        public string $username,
        public string $password,
    ) {
    }
}
