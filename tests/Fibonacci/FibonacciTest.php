<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Fibonacci;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class FibonacciTest extends TestCase
{
    // see https://www.wackerart.de/mathematik/big_numbers/fibonacci_numbers.html

    #[Test]
    #[TestDox('it should yield 0 for index 0')]
    public function itShouldYield0ForIndex0(): void
    {
        self::markTestIncomplete('impl me');
    }

    #[Test]
    public function itShouldThrowForNullIndex(): void
    {
        self::markTestIncomplete('impl me');
    }

    #[Test]
    public function itShouldThrowForNegativeIndex(): void
    {
        self::markTestIncomplete('impl me');
    }

    #[Test]
    public function itShouldThrowForIndexAbove46(): void
    {
        self::markTestIncomplete('impl me');
    }

    #[Test]
    public function itShouldYield1ForIndex1(): void
    {
        self::markTestIncomplete('impl me');
    }

    #[Test]
    public function itShouldYield1ForIndex2(): void
    {
        self::markTestIncomplete('impl me');
    }

    #[Test]
    public function itShouldYield2ForIndex3(): void
    {
        self::markTestIncomplete('impl me');
    }

    #[Test]
    public function itShouldYield3ForIndex4(): void
    {
        self::markTestIncomplete('impl me');
    }

    #[Test]
    public function itShouldYield5ForIndex5(): void
    {
        self::markTestIncomplete('impl me');
    }

    #[Test]
    public function itShouldYield8ForIndex6(): void
    {
        self::markTestIncomplete('impl me');
    }

    #[Test]
    public function itShouldYield55ForIndex10(): void
    {
        self::markTestIncomplete('impl me');
    }

    #[Test]
    public function itShouldYield6_765ForIndex20(): void
    {
        self::markTestIncomplete('impl me');
    }

    #[Test]
    public function itShouldYield832_040ForIndex30(): void
    {
        self::markTestIncomplete('impl me');
    }

    #[Test]
    public function itShouldYield102_334_155ForIndex40(): void
    {
        self::markTestIncomplete('impl me');
    }

    #[Test]
    public function itShouldYield1_836_311_903ForIndex46(): void
    {
        self::markTestIncomplete('impl me');
    }
}
