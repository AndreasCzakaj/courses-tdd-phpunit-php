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
        self::assertCount(3, $this->list);
    }

    #[Test]
    public function shouldContainA(): void
    {
        self::assertContains('a', $this->list);
    }

    #[Test]
    public function shouldNotContainD(): void
    {
        self::assertNotContains('d', $this->list);
    }

    #[Test]
    public function shouldContainCAndA(): void
    {
        self::assertContains('c', $this->list);
        self::assertContains('a', $this->list);

        // alternative: 1 expression
        self::assertEmpty(array_diff(['c', 'a'], $this->list));
    }

    #[Test]
    public function shouldNotContainDuplicates(): void
    {
        self::assertSame($this->list, array_unique($this->list));
    }

    #[Test]
    public function more(): void
    {
        // precisely: same items, same order
        self::assertSame(['a', 'b', 'c'], $this->list);
        // loosely: same items, any order
        self::assertEqualsCanonicalizing(['c', 'a', 'b'], $this->list);
        // contains any of
        self::assertNotEmpty(array_intersect(['c', 'a', 'b', 'd'], $this->list));
        // all items have the same type
        self::assertContainsOnlyString($this->list);
    }

    #[Test]
    public function map(): void
    {
        self::assertArrayHasKey('k1', $this->map);
        self::assertArrayNotHasKey('xxx', $this->map);
        self::assertContains('v2', $this->map);
        self::assertNotContains('yyy', $this->map);
        self::assertSame('v2', $this->map['k2']);
    }
}
