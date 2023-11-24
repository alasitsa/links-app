<?php

namespace App\Repositories\Interfaces;

use App\Models\Link;
use Illuminate\Database\Eloquent\Collection;

interface ILinkRepository
{
    /**
     * @param string $original
     * @param string $slug
     * @param int $userId
     * @return void
     */
    public function create(string $original, string $slug, int $userId): void;

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
     * @param string $original
     * @param int|null $userId
     * @return Link|null
     */
    public function getByOriginalLink(string $original, ?int $userId = null): ?Link;


    /**
     * @param int $id
     * @param string|null $original
     * @param string|null $slug
     * @param int|null $userId
     * @return void
     */
    public function patch(int $id, ?string $original = null, ?string $slug = null, ?int $userId = null): void;

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;

    /**
     * @param string $slug
     * @return bool
     */
    public function slugExists(string $slug): bool;

    /**
     * @param string $slug
     * @return Link|null
     */
    public function getBySlug(string $slug): ?Link;
}
