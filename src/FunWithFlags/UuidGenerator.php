<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\FunWithFlags;

interface UuidGenerator
{
    public function create(): string;
}
