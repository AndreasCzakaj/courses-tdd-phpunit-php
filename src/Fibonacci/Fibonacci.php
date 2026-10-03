<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Fibonacci;

interface Fibonacci
{
    public function calculate(?int $index): int;
}
