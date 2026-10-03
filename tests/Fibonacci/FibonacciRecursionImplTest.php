<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Fibonacci;

use BinaryStars\Tdd\Fibonacci\Fibonacci;
use BinaryStars\Tdd\Fibonacci\FibonacciRecursionImpl;

class FibonacciRecursionImplTest extends FibonacciTestBase
{
    protected function factory(): Fibonacci
    {
        return new FibonacciRecursionImpl();
    }
}
