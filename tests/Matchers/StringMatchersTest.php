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
        self::assertNotEquals(null, $this->email, 'email should not be null');
        self::assertNotNull($this->email, 'email should not be null');
    }

    #[Test]
    public function shouldBeAString(): void
    {
        self::markTestIncomplete('email should be a string');
    }

    #[Test]
    public function shouldBeAndreasCzakaj(): void
    {
        self::markTestIncomplete('email should be andreas.czakaj@binary-stars.eu');
    }

    #[Test]
    public function shouldStartWithAndreas(): void
    {
        self::markTestIncomplete('email should start with "andreas"');
    }

    #[Test]
    public function shouldEndWithDotEu(): void
    {
        self::markTestIncomplete('email should end with ".eu"');
    }

    #[Test]
    public function shouldNotEndWithDotCom(): void
    {
        self::markTestIncomplete('email should not end with ".com"');
    }

    #[Test]
    public function shouldContainBinary(): void
    {
        self::markTestIncomplete('email should contain "binary"');
    }

    #[Test]
    public function shouldContainAndreasAndStars(): void
    {
        self::markTestIncomplete('email should contain "andreas" and "stars"');
    }

    #[Test]
    public function shouldMatchRegex(): void
    {
        self::markTestIncomplete('email should match regular expression "/^[a-z.@\-]+$/"');
    }

    #[Test]
    public function shouldMatchAllInOne(): void
    {
        self::markTestIncomplete('TODO: all of the above in 1 expression');
    }
}
