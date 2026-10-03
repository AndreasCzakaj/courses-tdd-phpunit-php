<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Matchers;

class Person
{
    public function __construct(
        public ?int $id = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $email = null,
        public ?string $ipAddress = null,
    ) {
    }
}
