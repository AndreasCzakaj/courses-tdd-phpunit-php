<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\FunWithFlags;

use BinaryStars\Tdd\FunWithFlags\UuidGenerator;
use BinaryStars\Tdd\FunWithFlags\UuidGeneratorNaiveRandomImpl;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class UuidGeneratorTest extends TestCase
{
    #[Test]
    #[DataProvider('shouldCreateAUuidInTheMatchingFormatParams')]
    #[TestDox('it should match pattern $expectedRegex for case: $info')]
    public function shouldCreateAUuidInTheMatchingFormat(
        UuidGenerator $uuidGenerator,
        string $expectedRegex,
        string $info,
    ): void {
        // when
        $actual = $uuidGenerator->create();

        // then
        self::assertMatchesRegularExpression($expectedRegex, $actual, $info);
    }

    public static function shouldCreateAUuidInTheMatchingFormatParams(): array
    {
        $baseImpl = new UuidGeneratorNaiveRandomImpl();

        return [
            [$baseImpl, '/^[a-f0-9]{32}$/', 'lower case, no dashes'],
            // [new ???, '/^[A-F0-9]{32}$/', 'upper case, no dashes'],
            // [new ???, '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/', 'lower case, with dashes'],
            // [new ???, '/^[A-F0-9]{8}-[A-F0-9]{4}-[A-F0-9]{4}-[A-F0-9]{4}-[A-F0-9]{12}$/', 'upper case, with dashes'],
        ];
    }

    #[Test]
    public function shouldUseAllChars(): void
    {
        $hexChars = str_split('0123456789abcdef');
        $foundChars = [];

        $uuidGenerator = new UuidGeneratorNaiveRandomImpl();

        // yes, I'm looping. I need this because the process is random.
        for ($i = 0; $i < 10; $i++) {
            foreach (str_split($uuidGenerator->create()) as $char) {
                $foundChars[$char] = ($foundChars[$char] ?? 0) + 1;
            }
        }

        self::assertEqualsCanonicalizing($hexChars, array_map(strval(...), array_keys($foundChars)));
        self::assertGreaterThan(0, min($foundChars));
    }
}
