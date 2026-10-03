<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Fibonacci;

class FibonacciLoopImpl extends FibonacciImplBase
{
    protected function calculateInternal(int $index): int
    {
        $previousPrevious = 0;
        $previous = 1;
        $result = 0;
        for ($i = 2; $i <= $index; $i++) {
            $result = $previous + $previousPrevious;
            $previousPrevious = $previous;
            $previous = $result;
        }

        return $result;
    }
}
