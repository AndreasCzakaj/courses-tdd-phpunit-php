<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Uss;

use Exception;

class ValidationError extends Exception
{
    public function __construct(public readonly string $field)
    {
        parent::__construct("invalid: $field");
    }
}
