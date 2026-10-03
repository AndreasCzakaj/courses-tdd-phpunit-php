<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Matchers;

use RuntimeException;

class First
{
    public const PPL_JSON = __DIR__ . '/../../resources/ppl.json';

    /** @var array<string, string> */
    public array $map = [
        'k1' => 'v1',
        'k2' => 'v2',
    ];

    public function getEmail(): string
    {
        return 'andreas.czakaj@binary-stars.eu';
    }

    /** @return string[] */
    public function getList(): array
    {
        return ['a', 'b', 'c'];
    }

    /** @return Person[] */
    public function getPeople(): array
    {
        $items = json_decode(file_get_contents(self::PPL_JSON), true, flags: JSON_THROW_ON_ERROR);

        return array_map(
            fn (array $item) => new Person(
                id: $item['id'],
                firstName: $item['firstName'],
                lastName: $item['lastName'],
                email: $item['email'],
                ipAddress: $item['ipAddress'],
            ),
            $items,
        );
    }

    public function getPerson(): Person
    {
        throw new RuntimeException('oops');
    }
}
