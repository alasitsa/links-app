<?php

namespace App\Services\Interfaces;

use App\Exceptions\LinkExistsException;
use App\Exceptions\LinkForbidden;
use App\Exceptions\LinkNotExistException;
use App\Exceptions\LinkUnsafeException;
use App\Models\Link;
use Illuminate\Database\Eloquent\Collection;

interface ILinkService
{
    /**
     * @param int $id
     * @return Link|null
     * @throws LinkForbidden
     * @throws LinkNotExistException
     */
    public function get(int $id): ?Link;

    /**
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * @param int|null $id
     * @param string|null $original
     * @param string|null $slug
     * @return void
     * @throws LinkUnsafeException
     * @throws LinkExistsException
     */
    public function patch(?int $id = null, ?string $original = null, ?string $slug = null): void;

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;
}
