<?php

// Creates the database of the user self service: a SQLite file, filled with the accounts below.
// Run it with `composer uss`, see README.md.

declare(strict_types=1);

use BinaryStars\Tdd\Uss\Account;
use BinaryStars\Tdd\Uss\AccountDaoPdoImpl;

require __DIR__ . '/../vendor/autoload.php';

$accounts = [
    ['username' => 'alice_verified', 'password' => 'Correct-Horse_42', 'status' => Account::STATUS_VERIFIED],
    ['username' => 'bob_not_verified', 'password' => 'Battery.Staple+7', 'status' => Account::STATUS_NEW],
];

$dsn = require __DIR__ . '/uss-config.php';
$dbPath = substr($dsn, strlen('sqlite:'));
if (file_exists($dbPath)) {
    unlink($dbPath);
}

$accountDao = new AccountDaoPdoImpl($dsn);
$accountDao->createTable();

echo 'SQLite database: ', $dbPath, PHP_EOL;
foreach ($accounts as $account) {
    $accountDao->save(new Account(
        id: vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4)),
        username: $account['username'],
        passwordHash: password_hash($account['password'], PASSWORD_DEFAULT),
        email: $account['username'] . '@example.com',
        tcAccepted: new DateTimeImmutable(),
        status: $account['status'],
    ));
    echo '  ', json_encode($account), PHP_EOL;
}
