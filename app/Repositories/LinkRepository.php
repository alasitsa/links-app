<?php

namespace App\Repositories;

use App\Models\Link;
use App\Repositories\Interfaces\ILinkRepository;
use Illuminate\Database\Eloquent\Collection;

class LinkRepository implements ILinkRepository
{

    /**
     * @param string $original
     * @param string $slug
     * @param int $userId
     * @return void
     */
    public function create(string $original, string $slug, int $userId): void
    {
        $link = new Link();
        $link->slug = $slug;
        $link->original = $original;
        $link->user_id = $userId;
        $link->save();
    }

    /**
     * @param int $id
     * @return Link|null
     */
    public function get(int $id): ?Link
    {
        return Link::find($id);
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Link::all();
    }

    /**
     * @param int $userId
     * @return Collection
     */
    public function getByUser(int $userId): Collection
    {
        return Link::where('user_id', $userId);
    }

    /**
     * @param string $original
     * @param int|null $userId
     * @return Link|null
     */
    public function getByOriginalLink(string $original, ?int $userId = null): ?Link
    {
        $link = Link::where('original', $original);
        if ($userId) {
            $link = $link->where('user_id', $userId);
        }
        $link = $link->first();
        return $link;
    }

    /**
     * @param int $id
     * @param string|null $original
     * @param string|null $slug
     * @param int|null $userId
     * @return void
     */
    public function patch(int $id, ?string $original = null, ?string $slug = null, ?int $userId = null): void
    {
        $link = $this->get($id);
        if ($original) {
            $link->original = $original;
        }

        if ($slug) {
            $link->slug = $slug;
        }

        if ($userId) {
            $link->userId = $userId;
        }

        $link->save();
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $link = $this->get($id);
        $link->delete();
    }

    /**
     * @param string $slug
     * @return bool
     */
    public function slugExists(string $slug): bool
    {
        return Link::where('slug', $slug)->exists();
    }
}
