<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests;

use BinaryStars\Tdd\Hello;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class HelloTest extends TestCase
{
    private Hello $sut;

    protected function setUp(): void
    {
        $this->sut = new Hello();
    }

    #[Test]
    #[TestDox('It should yield 42 for the ultimate question')]
    public function init(): void
    {
        // given
        $input = 'What is the answer to the Ultimate Question of Life, the Universe, and Everything?';

        // when
        $actual = $this->sut->answer($input);

        // then
        self::assertSame(42, $actual, 'it should be as Douglas Adams said');
    }
}
