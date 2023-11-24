<?php

namespace App\Services;

use App\Exceptions\LoginFailed;
use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Interfaces\IUserService;
use Illuminate\Support\Facades\Hash;

class UserService implements IUserService
{
    private IUserRepository $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $email
     * @param string $password
     * @return User|null
     * @throws LoginFailed
     */
    public function login(string $email, string $password): ?User
    {
        $user = $this->userRepository->getByField('email', $email);
        if(!$user || !Hash::check($password, $user->password)) {
            throw new LoginFailed();
        }
        return $user;
    }
}
