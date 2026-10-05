<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Uss\Integration;

use BinaryStars\Tdd\Tests\Uss\Creators;
use BinaryStars\Tdd\Uss\AccountDaoPdoImpl;
use BinaryStars\Tdd\Uss\DaoError;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[RequiresPhpExtension('pdo_sqlite')]
class AccountDaoPdoImplTest extends TestCase
{
    private AccountDaoPdoImpl $dao;

    protected function setUp(): void
    {
        // a real SQLite database, in memory
        $this->dao = new AccountDaoPdoImpl('sqlite::memory:');
        $this->dao->createTable();
    }

    #[Test]
    public function shouldFindAnAccountByItsUsername(): void
    {
        // given
        $account = Creators::verifiedAccount();
        $this->dao->save($account);
        $this->dao->save(Creators::notVerifiedAccount());

        // when
        $actual = $this->dao->findByUsername($account->username);

        // then: the account as it was saved
        self::assertEquals($account, $actual);
    }

    #[Test]
    public function shouldReturnNullForAnUnknownUsername(): void
    {
        self::assertNull($this->dao->findByUsername('idonotexist'));
    }

    #[Test]
    public function shouldNotSaveTheSameUsernameTwice(): void
    {
        $this->dao->save(Creators::verifiedAccount());

        $this->expectExceptionObject(new DaoError('PDO Error'));

        $this->dao->save(Creators::verifiedAccount());
    }

    #[Test]
    public function shouldThrowADaoErrorIfTheDatabaseIsNotAvailable(): void
    {
        // given: a folder is not a database file
        $dao = new AccountDaoPdoImpl('sqlite:' . sys_get_temp_dir());

        // then
        $this->expectExceptionObject(new DaoError('PDO Error'));

        // when
        $dao->findByUsername('alice_verified');
    }
}
