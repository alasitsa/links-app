<?php

namespace App\Services;

use App\Actions\LinkCheckerAction;
use App\Actions\LinkSafeAction;
use App\Actions\LinkExistsAction;
use App\Actions\LinkHasherAction;
use App\Exceptions\LinkExistsException;
use App\Exceptions\LinkNotExistException;
use App\Exceptions\LinkUnsafeException;
use App\Models\Link;
use App\Repositories\Interfaces\ILinkRepository;
use App\Services\Interfaces\ILinkService;
use Illuminate\Database\Eloquent\Collection;

class LinkService implements ILinkService
{
    private ILinkRepository $linkRepository;
    private LinkHasherAction $linkHasherAction;
    private LinkCheckerAction $linkCheckerAction;

    /**
     * @param ILinkRepository $linkRepository
     * @param LinkHasherAction $linkHasherAction
     * @param LinkCheckerAction $linkCheckerAction
     */
    public function __construct(ILinkRepository $linkRepository, LinkHasherAction $linkHasherAction, LinkCheckerAction $linkCheckerAction)
    {
        $this->linkRepository = $linkRepository;
        $this->linkHasherAction = $linkHasherAction;
        $this->linkCheckerAction = $linkCheckerAction;
    }

    /**
     * @param string $original
     * @param string|null $slug
     * @param int $userId
     * @return void
     * @throws LinkExistsException
     * @throws LinkUnsafeException
     */
    public function create(int $userId, string $original, ?string $slug = null): void
    {
        if (!$slug) {
            $slug = ($this->linkHasherAction)($original);
        }

        ($this->linkCheckerAction)($original, $slug, $userId);
        $this->linkRepository->create($original, $slug, $userId);
    }

    /**
     * @param int $id
     * @return Link|null
     */
    public function get(int $id): ?Link
    {
        return $this->linkRepository->get($id);
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->linkRepository->getAll();
    }

    /**
     * @param int $userId
     * @return Collection
     */
    public function getByUser(int $userId): Collection
    {
        return $this->linkRepository->getByUser($userId);
    }

    /**
     * @param int $id
     * @param string|null $original
     * @param string|null $slug
     * @param int|null $userId
     * @return void
     * @throws LinkUnsafeException
     * @throws LinkExistsException
     */
    public function patch(int $id, ?string $original = null, ?string $slug = null, ?int $userId = null): void
    {
        ($this->linkCheckerAction)($original, $slug, $userId);
        $this->linkRepository->patch($id, $original, $slug, $userId);
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $this->linkRepository->delete($id);
    }

    /**
     * @param string $slug
     * @return string|null
     * @throws LinkNotExistException
     */
    public function getBySlug(string $slug): ?string
    {
        $link = $this->linkRepository->getBySlug($slug);
        if (!$link) {
            throw new LinkNotExistException();
        }
        return $link->original;
    }
}
