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

class AdminLinkService implements ILinkService
{
    private ILinkRepository $linkRepository;
    private LinkCheckerAction $linkCheckerAction;

    /**
     * @param ILinkRepository $linkRepository
     * @param LinkCheckerAction $linkCheckerAction
     */
    public function __construct(ILinkRepository $linkRepository, LinkCheckerAction $linkCheckerAction)
    {
        $this->linkRepository = $linkRepository;
        $this->linkCheckerAction = $linkCheckerAction;
    }

    /**
     * @param int $id
     * @return Link|null
     * @throws LinkNotExistException
     */
    public function get(int $id): ?Link
    {
        $link = $this->linkRepository->get($id);
        if (!$link) {
            throw new LinkNotExistException();
        }
        return $link;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->linkRepository->getAll();
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
        $link = $this->linkRepository->get($id);
        ($this->linkCheckerAction)($original, $slug, $link->user_id);
        $this->linkRepository->patch(id: $id, original: $original, slug: $slug);
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
