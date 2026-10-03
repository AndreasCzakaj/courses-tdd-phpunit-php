<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Matchers;

use BinaryStars\Tdd\Matchers\First;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class StringMatchersTest extends TestCase
{
    private First $first;
    private string $email;

    protected function setUp(): void
    {
        $this->first = new First();
        $this->email = $this->first->getEmail();
    }

    #[Test]
    public function shouldNotBeNull(): void
    {
        self::assertNotNull($this->email);
    }

    #[Test]
    public function shouldBeAString(): void
    {
        self::assertIsString($this->email);
    }

    #[Test]
    public function shouldBeAndreasCzakaj(): void
    {
        // assertSame: === (type + value), assertEquals: == (loose)
        self::assertSame('andreas.czakaj@binary-stars.eu', $this->email);
        self::assertEqualsIgnoringCase('ANDREAS.czakaj@binary-stars.eu', $this->email);
    }

    #[Test]
    public function shouldStartWithAndreas(): void
    {
        self::assertStringStartsWith('andreas', $this->email);
    }

    #[Test]
    public function shouldEndWithDotEu(): void
    {
        self::assertStringEndsWith('.eu', $this->email);
    }

    #[Test]
    public function shouldNotEndWithDotCom(): void
    {
        self::assertStringEndsNotWith('.com', $this->email);
    }

    #[Test]
    public function shouldContainBinary(): void
    {
        self::assertStringContainsString('binary', $this->email);
    }

    #[Test]
    public function shouldContainAndreasAndStars(): void
    {
        self::assertThat($this->email, self::logicalAnd(
            self::stringContains('andreas'),
            self::stringContains('stars'),
        ));
    }

    #[Test]
    public function shouldMatchRegex(): void
    {
        // the last arg is the message, like AssertJ's `.as(...)`
        self::assertMatchesRegularExpression(
            '/^[0-9a-z.@\-]+$/',
            $this->email,
            'it should match super simplistic reg exp',
        );
    }

    #[Test]
    public function shouldMatchAllInOne(): void
    {
        // every assertXyz(...) is a shortcut for assertThat($actual, constraint)
        // ... and constraints can be combined
        self::assertThat($this->email, self::logicalAnd(
            self::logicalNot(self::isNull()),
            self::isString(),
            self::identicalTo('andreas.czakaj@binary-stars.eu'),
            self::stringStartsWith('andreas'),
            self::stringEndsWith('.eu'),
            self::logicalNot(self::stringEndsWith('.com')),
            self::stringContains('binary'),
            self::matchesRegularExpression('/^[0-9a-z.@\-]+$/'),
        ));

        // Note: PHPUnit has no "soft assertions",
        // i.e. a test always stops at its 1st failing assertion
    }
}
