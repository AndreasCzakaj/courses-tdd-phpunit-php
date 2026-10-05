<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Uss;

/**
 * What a controller returns: independent of how the response is sent.
 */
final readonly class HttpResult
{
    public function __construct(
        public int $status,
        // encoded as JSON
        public mixed $body,
    ) {
    }
}
