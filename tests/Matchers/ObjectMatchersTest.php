<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Matchers;

use BinaryStars\Tdd\Matchers\First;
use BinaryStars\Tdd\Matchers\Person;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ObjectMatchersTest extends TestCase
{
    private First $first;
    /** @var Person[] */
    private array $people;
    private Person $firstPerson;

    protected function setUp(): void
    {
        $this->first = new First();
        $this->people = $this->first->getPeople();
        $this->firstPerson = $this->people[0];
    }

    #[Test]
    public function peopleShouldContain1000People(): void
    {
        self::markTestIncomplete('people should contain 1000 people');
    }

    #[Test]
    public function testFirstPerson(): void
    {
        self::markTestIncomplete('first person should have the expected values, field by field');

        $expected = new Person(
            id: 1,
            firstName: 'Skippy',
            lastName: 'Rayne',
            email: 'srayne0@dot.gov',
            ipAddress: '229.183.132.150',
        );
    }

    #[Test]
    public function testFirstPersonInOneGo(): void
    {
        self::markTestIncomplete('first person should equal the expected person, in one go');

        $expected = new Person(
            id: 1,
            firstName: 'Skippy',
            lastName: 'Rayne',
            email: 'srayne0@dot.gov',
            ipAddress: '229.183.132.150',
        );
    }

    #[Test]
    public function testFirstPersonPartially(): void
    {
        self::markTestIncomplete('first person should match id, firstName and lastName only');

        $expected = new Person(id: 1, firstName: 'Skippy', lastName: 'Rayne');
    }

    #[Test]
    public function testGetPersonThrowsException(): void
    {
        self::markTestIncomplete('getPerson should throw a RuntimeException with message "oops" (2 variants)');
    }
}
