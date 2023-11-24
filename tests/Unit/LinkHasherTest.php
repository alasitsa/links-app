<?php

namespace Tests\Unit;

use App\Actions\LinkHasherAction;
use PHPUnit\Framework\TestCase;

class LinkHasherTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @dataProvider linksProvider
     */
    public function testHasher($original, $slug): void
    {
        $linkHasher = new LinkHasherAction();

        $hash = $linkHasher($original, 8);
        $this->assertEquals($slug, $hash);
        $this->assertEquals(8, strlen($hash));
    }

    public static function linksProvider(): array
    {
        return [
            ["https://youtube.com", "e62e2446"],
            ["https://google.com", "99999ebc"],
            ["https://github.com", "3097fca9"]
        ];
    }
}
