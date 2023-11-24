<?php

namespace Tests\Feature;

use App\Repositories\LinkRepository;
use Tests\TestCase;

class LinkPatchTest extends TestCase
{
    /**
     * A basic feature test example.
     * @dataProvider linkProvider
     */
    public function testPatch($original, $slug, $patchedOriginal, $userId): void
    {
        $linkRepository = new LinkRepository();
        $linkRepository->create($original, $slug, $userId);
        $link = $linkRepository->getByOriginalLink($original, $userId);
        $linkRepository->patch($link->id, original: $patchedOriginal);
        $updatedLink = $linkRepository->get($link->id);
        $this->assertEquals($link->slug, $updatedLink->slug);
        $this->assertEquals($patchedOriginal, $updatedLink->original);
        $this->assertEquals($slug, $updatedLink->slug);
        $linkRepository->delete($link->id);
        $this->assertNull($linkRepository->get($link->id));
    }

    public static function linkProvider(): array
    {
        return [
            ["https://youtube.com", "ytTEST", "https://google.com", 1],
            ["https://github.com", "ghTEST", "https://gmail.com", 1]
        ];
    }
}
