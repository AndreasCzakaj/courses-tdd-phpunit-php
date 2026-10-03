<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Fibonacci;

use BinaryStars\Tdd\Fibonacci\Fibonacci;
use BinaryStars\Tdd\Fibonacci\FibonacciLoopImpl;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\TestWith;

class FibonacciLoopImplTest extends FibonacciTestBase
{
    protected function factory(): Fibonacci
    {
        return new FibonacciLoopImpl();
    }

    // #[TestWith]: data sets w/out a data provider method
    #[Test]
    #[TestWith([30, 832_040])]
    #[TestWith([40, 102_334_155])]
    #[TestWith([46, 1_836_311_903])]
    #[TestDox('it should yield $expected for index #$index')]
    public function shouldPassForLargeNumbers(int $index, int $expected): void
    {
        self::assertSame($expected, $this->fibonacci->calculate($index));
    }
}
