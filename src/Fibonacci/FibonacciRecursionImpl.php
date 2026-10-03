<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Fibonacci;

class FibonacciRecursionImpl extends FibonacciImplBase
{
    protected function calculateInternal(int $index): int
    {
        return $this->calculate($index - 2) + $this->calculate($index - 1);
    }
}
