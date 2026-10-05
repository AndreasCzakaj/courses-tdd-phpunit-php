<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Uss;

use BinaryStars\Tdd\Uss\AccountDaoArrayImpl;
use BinaryStars\Tdd\Uss\AccountDaoThrowingImpl;
use BinaryStars\Tdd\Uss\DaoError;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class AccountDaoTest extends TestCase
{
    #[Test]
    public function arrayImplShouldFindAnAccountByItsUsername(): void
    {
        $account = Creators::verifiedAccount();
        $dao = new AccountDaoArrayImpl($account);

        self::assertSame($account, $dao->findByUsername($account->username));
    }

    #[Test]
    public function arrayImplShouldReturnNullForAnUnknownUsername(): void
    {
        $dao = new AccountDaoArrayImpl();

        self::assertNull($dao->findByUsername('idonotexist'));
    }

    #[Test]
    public function throwingImplShouldThrowADaoError(): void
    {
        $dao = new AccountDaoThrowingImpl();

        $this->expectExceptionObject(new DaoError('findByUsername: oops'));

        $dao->findByUsername('anyone');
    }
}
