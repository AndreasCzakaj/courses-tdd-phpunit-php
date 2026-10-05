<?php

// Creates the database of the user self service: a SQLite file, filled with the accounts below.
// Run it with `composer uss`, see README.md.

declare(strict_types=1);

$accounts = [
    ['username' => 'alice_verified', 'password' => 'Correct-Horse_42', 'status' => 'verified'],
    ['username' => 'bob_not_verified', 'password' => 'Battery.Staple+7', 'status' => 'new'],
];

$dbPath = getenv('USS_DB') ?: sys_get_temp_dir() . '/uss.sqlite';
if (file_exists($dbPath)) {
    unlink($dbPath);
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->exec(
    'CREATE TABLE accounts ('
    . 'id TEXT PRIMARY KEY, username TEXT UNIQUE, password_hash TEXT, email TEXT, tc_accepted TEXT, status TEXT)'
);
$insert = $pdo->prepare('INSERT INTO accounts VALUES (?, ?, ?, ?, ?, ?)');

echo 'SQLite database: ', $dbPath, PHP_EOL;
foreach ($accounts as $account) {
    $insert->execute([
        vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4)),
        $account['username'],
        password_hash($account['password'], PASSWORD_DEFAULT),
        $account['username'] . '@example.com',
        date(DATE_ATOM),
        $account['status'],
    ]);
    echo '  ', json_encode($account), PHP_EOL;
}
