<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Uss;

class AccountDaoArrayImpl implements AccountDao
{
    /** @var array<string, Account> */
    private array $repo = [];

    public function __construct(Account ...$accounts)
    {
        foreach ($accounts as $account) {
            $this->repo[$account->username] = $account;
        }
    }

    public function findByUsername(string $username): ?Account
    {
        return $this->repo[$username] ?? null;
    }
}
