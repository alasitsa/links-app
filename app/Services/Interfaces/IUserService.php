<?php

namespace App\Services\Interfaces;

use App\Exceptions\LoginFailed;
use App\Models\User;

interface IUserService
{
    /**
     * @param string $email
     * @param string $password
     * @return User|null
     * @throws LoginFailed
     */
    public function login(string $email, string $password): ?User;
}
