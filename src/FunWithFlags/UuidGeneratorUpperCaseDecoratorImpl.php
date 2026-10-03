<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\FunWithFlags;

/**
 * Decorator pattern: a decorator IS a UuidGenerator and HAS a UuidGenerator.
 * It delegates the work and adds its own functionality to the result.
 */
class UuidGeneratorUpperCaseDecoratorImpl implements UuidGenerator
{
    public function __construct(private readonly UuidGenerator $delegate)
    {
    }

    public function create(): string
    {
        return strtoupper($this->delegate->create());
    }
}
