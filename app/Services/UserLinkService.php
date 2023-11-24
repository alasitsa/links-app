<?php

namespace App\Services;

use App\Actions\LinkCheckerAction;
use App\Actions\LinkHasherAction;
use App\Exceptions\LinkExistsException;
use App\Exceptions\LinkForbidden;
use App\Exceptions\LinkNotExistException;
use App\Exceptions\LinkUnsafeException;
use App\Models\Link;
use App\Repositories\Interfaces\ILinkRepository;
use App\Services\Interfaces\ILinkService;
use Illuminate\Database\Eloquent\Collection;

class UserLinkService implements ILinkService
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
     * @param int $id
     * @return Link|null
     * @throws LinkForbidden
     * @throws LinkNotExistException
     */
    public function get(int $id): ?Link
    {
        $link = $this->linkRepository->get($id);
        if (!$link) {
            throw new LinkNotExistException();
        }

        if ($link->user_id != auth()->user()->id) {
            throw new LinkForbidden();
        }
        return $link;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->linkRepository->getByUser(auth()->user()->id);
    }

    /**
     * @param int|null $id
     * @param string|null $original
     * @param string|null $slug
     * @return void
     * @throws LinkUnsafeException
     * @throws LinkExistsException
     */
    public function patch(?int $id = null, ?string $original = null, ?string $slug = null): void
    {
        $userId = auth()->user()->id;
        ($this->linkCheckerAction)($original, $slug, $userId);
        $id
            ? $this->linkRepository->patch(id: $id, original: $original, slug: $slug)
            : $this->linkRepository->create(original: $original, slug: $slug ?? ($this->linkHasherAction)($original), userId: $userId);
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $this->linkRepository->delete($id);
    }
}
