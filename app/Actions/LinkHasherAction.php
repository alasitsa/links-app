<?php

namespace App\Actions;

class LinkHasherAction
{
    /**
     * @param string $original
     * @param int|null $length
     * @return string
     */
    public function __invoke(string $original, ?int $length = null): string
    {
        return substr(md5($original), 0, $length ?: env("LINK_LENGTH"));
    }
}
