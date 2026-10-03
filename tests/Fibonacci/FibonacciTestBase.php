<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Fibonacci;

use BinaryStars\Tdd\Fibonacci\Fibonacci;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

/**
 * Reusable tests: every implementation of `Fibonacci` must pass them.
 *
 * Not executed by PHPUnit itself: the class is abstract and the file name
 * does not end with `Test.php`. The subclasses are.
 */
abstract class FibonacciTestBase extends TestCase
{
    protected Fibonacci $fibonacci;

    abstract protected function factory(): Fibonacci;

    protected function setUp(): void
    {
        $this->fibonacci = $this->factory();
    }

    // see https://www.wackerart.de/mathematik/big_numbers/fibonacci_numbers.html

    #[Test]
    #[DataProvider('shouldPassForSmallNumbersParams')]
    #[TestDox('it should yield $expected for index #$index')]
    public function shouldPassForSmallNumbers(int $index, int $expected): void
    {
        $actual = $this->fibonacci->calculate($index);
        self::assertSame($expected, $actual);
    }

    public static function shouldPassForSmallNumbersParams(): array
    {
        return [
            [0, 0],
            [1, 1],
            [2, 1],
            [3, 2],
            [4, 3],
            [5, 5],
            [6, 8],
            [10, 55],
            [19, 4_181],
            [20, 6_765],
        ];
    }

    #[Test]
    #[DataProvider('shouldFailParams')]
    #[TestDox('it should throw "$expected" for index #$index')]
    public function shouldFail(?int $index, string $expected): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expected);

        $this->fibonacci->calculate($index);
    }

    public static function shouldFailParams(): array
    {
        // the keys are optional: they name the data sets
        return [
            'null' => [null, 'index must not be null'],
            'negative' => [-1, 'index must not be negative'],
            'above 46' => [47, 'index must not be > 46'],
        ];
    }
}
