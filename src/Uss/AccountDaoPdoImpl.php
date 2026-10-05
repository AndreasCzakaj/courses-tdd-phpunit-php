<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Uss;

use DateTimeImmutable;
use PDO;
use PDOException;
use PDOStatement;

class AccountDaoPdoImpl implements AccountDao
{
    private const CREATE_TABLE = <<<'SQL'
        CREATE TABLE IF NOT EXISTS accounts (
            id TEXT PRIMARY KEY,
            username TEXT UNIQUE,
            password_hash TEXT,
            email TEXT,
            tc_accepted TEXT,
            status TEXT
        )
        SQL;
    private const INSERT = <<<'SQL'
        INSERT INTO accounts (id, username, password_hash, email, tc_accepted, status)
        VALUES (?, ?, ?, ?, ?, ?)
        SQL;
    private const SELECT_BY_USERNAME = <<<'SQL'
        SELECT id, username, password_hash, email, tc_accepted, status
        FROM accounts WHERE username = ?
        SQL;

    private ?PDO $pdo = null;

    /** @param string $dsn e.g. "sqlite:/path/to/file" or "sqlite::memory:" */
    public function __construct(private readonly string $dsn)
    {
    }

    public function createTable(): void
    {
        $this->execute(self::CREATE_TABLE);
    }

    public function save(Account $account): void
    {
        $this->execute(self::INSERT, [
            $account->id,
            $account->username,
            $account->passwordHash,
            $account->email,
            $account->tcAccepted->format(DATE_ATOM),
            $account->status,
        ]);
    }

    public function findByUsername(string $username): ?Account
    {
        $row = $this->execute(self::SELECT_BY_USERNAME, [$username])->fetch(PDO::FETCH_ASSOC);

        return $row === false ? null : self::toAccount($row);
    }

    /** @param list<string> $params */
    private function execute(string $sql, array $params = []): PDOStatement
    {
        try {
            // connects on first use: an unavailable database is an error of the request, not of the startup
            $this->pdo ??= new PDO($this->dsn, options: [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $statement = $this->pdo->prepare($sql);
            $statement->execute($params);

            return $statement;
        } catch (PDOException $e) {
            throw new DaoError('PDO Error', previous: $e);
        }
    }

    /** @param array<string, string> $row */
    private static function toAccount(array $row): Account
    {
        return new Account(
            id: $row['id'],
            username: $row['username'],
            passwordHash: $row['password_hash'],
            email: $row['email'],
            tcAccepted: new DateTimeImmutable($row['tc_accepted']),
            status: $row['status'],
        );
    }
}
