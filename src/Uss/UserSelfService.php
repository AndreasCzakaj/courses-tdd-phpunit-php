<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Uss;

class UserSelfService
{
    public function __construct(public AccountDao $accountDao)
    {
    }

    public function login(mixed $credentials): UserSession
    {
        $validCredentials = CredentialsValidator::validateCredentials($credentials);

        $account = $this->findAccount($validCredentials->username);
        // password_verify() compares in constant time
        if ($account === null || !password_verify($validCredentials->password, $account->passwordHash)) {
            throw new AuthenticationError();
        }
        if ($account->status !== Account::STATUS_VERIFIED) {
            throw new AccountNotVerifiedError();
        }

        return new UserSession(accountId: $account->id, username: $account->username, email: $account->email);
    }

    private function findAccount(string $username): ?Account
    {
        try {
            return $this->accountDao->findByUsername($username);
        } catch (DaoError $e) {
            throw new ServerError('Database not available. Try later.', previous: $e);
        }
    }
}
