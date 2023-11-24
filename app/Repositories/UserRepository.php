<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;

class UserRepository implements IUserRepository
{

    /**
     * @param int $id
     * @return User|null
     */
    public function get(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * @param string $field
     * @param mixed $value
     * @return User|null
     */
    public function getByField(string $field, mixed $value): ?User
    {
        return User::where($field, $value)->first();
    }
}
