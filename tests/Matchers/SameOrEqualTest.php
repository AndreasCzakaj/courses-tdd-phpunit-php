<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Matchers;

use BinaryStars\Tdd\Matchers\Person;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Same or equal? No exercise, but an illustration: all tests are GREEN.
 *
 * - `assertSame` is `===`: strict equality for scalars and arrays, IDENTITY for objects
 * - `assertEquals` is `==`: loose equality, i.e. PHP converts the types if needed
 */
class SameOrEqualTest extends TestCase
{
    #[Test]
    public function scalarsOfTheSameTypeAndValueAreSameAndEqual(): void
    {
        self::assertSame(42, 42);
        self::assertEquals(42, 42);
    }

    #[Test]
    public function scalarsOfDifferentTypesAreEqualButNotSame(): void
    {
        // `==` converts the types ...
        self::assertEquals(42, '42');
        self::assertEquals(42, 42.0);
        self::assertEquals(1, true);

        // ... `===` does not
        self::assertNotSame(42, '42');
        self::assertNotSame(42, 42.0);
        self::assertNotSame(1, true);
    }

    #[Test]
    public function arraysAreSameIfTheyHaveTheSameItemsInTheSameOrder(): void
    {
        // arrays are values, not objects: there is no identity
        self::assertSame(['a' => 1, 'b' => 2], ['a' => 1, 'b' => 2]);

        // other order of the keys: equal, but not same
        self::assertEquals(['a' => 1, 'b' => 2], ['b' => 2, 'a' => 1]);
        self::assertNotSame(['a' => 1, 'b' => 2], ['b' => 2, 'a' => 1]);

        // other types of the values: equal, but not same
        self::assertEquals([1, 2], ['1', '2']);
        self::assertNotSame([1, 2], ['1', '2']);
    }

    #[Test]
    public function objectsWithTheSameValuesAreEqualButNotSame(): void
    {
        $kim = new Person(id: 24, firstName: 'Kim');
        $otherKim = new Person(id: 24, firstName: 'Kim');

        // same class, same values
        self::assertEquals($kim, $otherKim);

        // ... but 2 instances
        self::assertNotSame($kim, $otherKim);
    }

    #[Test]
    public function anObjectIsOnlyTheSameAsItself(): void
    {
        $kim = new Person(id: 24, firstName: 'Kim');
        $alsoKim = $kim;

        // 2 variables, 1 instance
        self::assertSame($kim, $alsoKim);

        // a clone is a new instance
        self::assertNotSame($kim, clone $kim);
        self::assertEquals($kim, clone $kim);
    }

    #[Test]
    public function objectsWithDifferentValuesAreNotEqual(): void
    {
        $kim = new Person(id: 24, firstName: 'Kim');
        $joey = new Person(id: 24, firstName: 'Joey');

        self::assertNotEquals($kim, $joey);
    }

    #[Test]
    public function objectsAreEqualEvenIfTheTypesOfTheirValuesDiffer(): void
    {
        // the values of the properties are compared with `==`, too
        self::assertEquals($this->box(42), $this->box('42'));

        // to be strict, compare the values with `assertSame`
        self::assertNotSame($this->box(42)->value, $this->box('42')->value);
    }

    #[Test]
    public function objectsOfDifferentClassesAreNotEqual(): void
    {
        $box = $this->box(42);
        $other = (object) ['value' => 42];

        // same property, same value ...
        self::assertSame($box->value, $other->value);

        // ... but another class
        self::assertNotEquals($box, $other);
    }

    /** An object with 1 property of any type */
    private function box(mixed $value): object
    {
        return new class ($value) {
            public function __construct(public mixed $value)
            {
            }
        };
    }
}
