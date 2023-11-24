<?php

namespace App\Actions;

use App\Exceptions\LinkExistsException;
use App\Exceptions\LinkUnsafeException;

class LinkCheckerAction
{
    private LinkExistsAction $linkExistsAction;
    private LinkSafeAction $linkSafeAction;

    /**
     * @param LinkExistsAction $linkExistsAction
     * @param LinkSafeAction $linkSafeAction
     */
    public function __construct(LinkExistsAction $linkExistsAction, LinkSafeAction $linkSafeAction)
    {
        $this->linkExistsAction = $linkExistsAction;
        $this->linkSafeAction = $linkSafeAction;
    }

    /**
     * @param string|null $original
     * @param string|null $slug
     * @param int|null $userId
     *
     * @return bool
     *
     * @throws LinkExistsException
     * @throws LinkUnsafeException
     */
    public function __invoke(?string $original, ?string $slug, ?int $userId): bool
    {
        if (($this->linkExistsAction)($original, $slug, $userId)) {
            throw new LinkExistsException();
        }

        if ($original && !($this->linkSafeAction)($original)) {
            throw new LinkUnsafeException();
        }

        return true;
    }
}
