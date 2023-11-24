<?php

namespace App\Services\Interfaces;

use App\Exceptions\LinkExistsException;
use App\Exceptions\LinkNotExistException;
use App\Exceptions\LinkUnsafeException;
use App\Models\Link;
use Illuminate\Database\Eloquent\Collection;

interface ILinkService
{
    /**
     * @param string $original
     * @param string|null $slug
     * @param int $userId
     * @return void
     * @throws LinkExistsException
     * @throws LinkUnsafeException
     */
    public function create(int $userId, string $original, ?string $slug = null): void;

    /**
     * @param int $id
     * @return Link|null
     */
    public function get(int $id): ?Link;

    /**
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * @param int $userId
     * @return Collection
     */
    public function getByUser(int $userId): Collection;


    /**
     * @param int $id
     * @param string|null $original
     * @param string|null $slug
     * @param int|null $userId
     * @return void
     * @throws LinkExistsException
     * @throws LinkUnsafeException
     */
    public function patch(int $id, ?string $original = null, ?string $slug = null, ?int $userId = null): void;

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;

    /**
     * @param string $slug
     * @return string|null
     * @throws LinkNotExistException
     */
    public function getBySlug(string $slug): ?string;
}
