<?php

namespace App\Actions;

use App\Repositories\Interfaces\ILinkRepository;

class LinkExistsAction
{
    private ILinkRepository $linkRepository;

    /**
     * @param ILinkRepository $linkRepository
     */
    public function __construct(ILinkRepository $linkRepository)
    {
        $this->linkRepository = $linkRepository;
    }

    /**
     * @param string|null $original
     * @param string|null $slug
     * @param int|null $userId
     * @return bool
     */
    public function __invoke(?string $original, ?string $slug, ?int $userId): bool
    {
        if ($slug && $this->linkRepository->slugExists($slug)) { // unique slug
            return true;
        }

        if ($original && $userId) {
            $link = $this->linkRepository->getByOriginalLink($original); // if user already created this original
            if ($link && $link->userId = $userId) {
                return true;
            }
        }

        return false;
    }
}
