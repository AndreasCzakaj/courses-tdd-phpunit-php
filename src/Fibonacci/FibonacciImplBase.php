<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Fibonacci;

use InvalidArgumentException;

abstract class FibonacciImplBase implements Fibonacci
{
    final public function calculate(?int $index): int
    {
        $this->check($index);

        if ($index < 2) {
            return $index;
        }

        return $this->calculateInternal($index);
    }

    abstract protected function calculateInternal(int $index): int;

    private function check(?int $index): void
    {
        if ($index === null) {
            throw new InvalidArgumentException('index must not be null');
        }
        if ($index < 0) {
            throw new InvalidArgumentException('index must not be negative');
        }
        if ($index > 46) {
            throw new InvalidArgumentException('index must not be > 46');
        }
    }
}
