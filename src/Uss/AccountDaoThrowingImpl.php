<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Uss;

class AccountDaoThrowingImpl implements AccountDao
{
    public function findByUsername(string $username): ?Account
    {
        throw new DaoError('findByUsername: oops');
    }
}
