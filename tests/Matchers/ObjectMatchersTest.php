<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Matchers;

use BinaryStars\Tdd\Matchers\First;
use BinaryStars\Tdd\Matchers\Person;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

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
        self::assertCount(1000, $this->people);
        self::assertContainsOnlyInstancesOf(Person::class, $this->people);
    }

    #[Test]
    public function testFirstPerson(): void
    {
        $expected = new Person(
            id: 1,
            firstName: 'Skippy',
            lastName: 'Rayne',
            email: 'srayne0@dot.gov',
            ipAddress: '229.183.132.150',
        );

        self::assertSame($expected->id, $this->firstPerson->id);
        self::assertSame($expected->firstName, $this->firstPerson->firstName);
        self::assertSame($expected->lastName, $this->firstPerson->lastName);
        self::assertSame($expected->email, $this->firstPerson->email);
        self::assertSame($expected->ipAddress, $this->firstPerson->ipAddress);

        // batch!
        self::assertEquals($expected, $this->firstPerson);
    }

    #[Test]
    public function testFirstPersonInOneGo(): void
    {
        $expected = new Person(
            id: 1,
            firstName: 'Skippy',
            lastName: 'Rayne',
            email: 'srayne0@dot.gov',
            ipAddress: '229.183.132.150',
        );

        // assertSame: === => for objects: the very same instance
        self::assertNotSame($expected, $this->firstPerson);

        // assertEquals: == => for objects: same class, same property values
        self::assertEquals($expected, $this->firstPerson);
    }

    #[Test]
    public function testFirstPersonPartially(): void
    {
        $expected = new Person(id: 1, firstName: 'Skippy', lastName: 'Rayne');

        // variant 1: selected fields
        self::assertArrayIsEqualToArrayOnlyConsideringListOfKeys(
            (array) $expected,
            (array) $this->firstPerson,
            ['id', 'firstName', 'lastName'],
        );

        // variant 2: subtracted fields
        self::assertArrayIsEqualToArrayIgnoringListOfKeys(
            (array) $expected,
            (array) $this->firstPerson,
            ['email', 'ipAddress'],
        );

        // variant 3: compact
        self::assertSame(
            [1, 'Skippy', 'Rayne'],
            [$this->firstPerson->id, $this->firstPerson->firstName, $this->firstPerson->lastName],
        );
    }

    // Testing exceptions: tell PHPUnit what to expect BEFORE the action.
    // The test ends with the exception => only 1 variant per test

    #[Test]
    public function testGetPersonThrowsException(): void
    {
        // variant 1
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('oops');

        $this->first->getPerson();
    }

    #[Test]
    public function testGetPersonThrowsExceptionObject(): void
    {
        // variant 2
        $this->expectExceptionObject(new RuntimeException('oops'));

        $this->first->getPerson();
    }
}
