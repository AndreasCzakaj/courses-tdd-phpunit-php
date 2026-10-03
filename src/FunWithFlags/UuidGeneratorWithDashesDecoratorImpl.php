<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\FunWithFlags;

class UuidGeneratorWithDashesDecoratorImpl implements UuidGenerator
{
    public function __construct(private readonly UuidGenerator $delegate)
    {
    }

    public function create(): string
    {
        $uuid = $this->delegate->create();

        return implode('-', [
            substr($uuid, 0, 8),
            substr($uuid, 8, 4),
            substr($uuid, 12, 4),
            substr($uuid, 16, 4),
            substr($uuid, 20),
        ]);
    }
}
