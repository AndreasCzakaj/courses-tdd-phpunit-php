<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Matchers;

use BinaryStars\Tdd\Matchers\First;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CollectionMatchersTest extends TestCase
{
    /** @var string[] */
    private array $list;
    /** @var array<string, string> */
    private array $map;

    protected function setUp(): void
    {
        $first = new First();
        $this->list = $first->getList();
        $this->map = $first->map;
    }

    #[Test]
    public function shouldContain3Elements(): void
    {
        self::markTestIncomplete('list should contain 3 elements');
    }

    #[Test]
    public function shouldContainA(): void
    {
        self::markTestIncomplete('list should contain "a"');
    }

    #[Test]
    public function shouldNotContainD(): void
    {
        self::markTestIncomplete('list should not contain "d"');
    }

    #[Test]
    public function shouldContainCAndA(): void
    {
        self::markTestIncomplete('list should contain "c" and "a"');
    }

    #[Test]
    public function shouldNotContainDuplicates(): void
    {
        self::markTestIncomplete('list should not contain duplicates');
    }

    #[Test]
    public function more(): void
    {
        self::markTestIncomplete('list should be precisely a, b, c ... but also loosely c, a, b');
    }

    #[Test]
    public function map(): void
    {
        self::markTestIncomplete(
            'map should have key "k1", no key "xxx", value "v2", no value "yyy", and item k2 => v2'
        );
    }
}
