<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Uss;

/**
 * An interface on purpose: the service is isolated from the database,
 * and the implementations are interchangeable.
 */
interface AccountDao
{
    public function findByUsername(string $username): ?Account;
}
