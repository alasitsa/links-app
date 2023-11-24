<?php

namespace Tests\Feature;

use App\Repositories\LinkRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LinkGetByOriginalTest extends TestCase
{
    /**
     * A basic feature test example.
     * @dataProvider linksProvider
     */
    public function testGet($original, $slug, $userId): void
    {
        $linkRepository = new LinkRepository();
        $linkRepository->create($original, $slug, $userId);
        $link = $linkRepository->getByOriginalLink($original, $userId);
        $this->assertEquals($original, $link->original);
        $this->assertEquals($slug, $link->slug);
        $this->assertEquals($userId, $link->user_id);
        $linkRepository->delete($link->id);
        $this->assertNull($linkRepository->get($link->id));
    }

    public static function linksProvider(): array
    {
        return [
            ["https://youtube.com", "yt", 1],
            ["https://github.com", "gh", 1]
        ];
    }
}
