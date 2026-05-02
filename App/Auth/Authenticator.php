<?php

namespace App\Auth;

use App\Models\User;
use Framework\Auth\SessionAuthenticator;
use Framework\Core\IIdentity;

class Authenticator extends SessionAuthenticator
{
    protected function authenticate(string $username, string $password): ?IIdentity
    {
        $email = $username;
        $users = User::getAll('`email` = ?', [$email]);
        if (count($users) === 0) {
            return null;
        }
        $user = $users[0];
        if (password_verify($password, $user->getPasswordHash())) {
            return $user;
        }
        return null;
    }
}