<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface IUserRepository
{
    /**
     * @param int $id
     * @return User|null
     */
    public function get(int $id): ?User;

    /**
     * @param string $field
     * @param mixed $value
     * @return User|null
     */
    public function getByField(string $field, mixed $value): ?User;
}
