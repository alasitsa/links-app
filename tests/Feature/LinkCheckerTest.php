<?php

namespace Tests\Feature;

use App\Actions\LinkCheckerAction;
use App\Actions\LinkExistsAction;
use App\Actions\LinkSafeAction;
use App\Exceptions\LinkExistsException;
use App\Repositories\Interfaces\ILinkRepository;
use App\Repositories\LinkRepository;
use GuzzleHttp\Client;
use Mockery;
use Tests\TestCase;

class LinkCheckerTest extends TestCase
{
    private ILinkRepository $linkRepository;
    public function setUp(): void
    {
        parent::setup();
        $this->linkRepository = new LinkRepository();

        $this->mockedClient =
            Mockery::mock(Client::class);
        app()->instance(Client::class,
            $this->mockedClient);
    }

    /**
     * A basic feature test example.
     * @dataProvider linkCreateProvider
     */
    public function testCreate(string $original, string $slug, int $userId): void
    {
        $this->linkRepository->create($original, $slug, $userId);
    }

    /**
     * A basic feature test example.
     * @depends      testCreate
     * @dataProvider linkCheckerProvider
     */
    function testChecker(string $original, string $slug, int $userId, string|null $err)
    {
        $linkCheckerAction = new LinkCheckerAction(new LinkExistsAction($this->linkRepository), new LinkSafeAction($this->mockedClient));
        if ($err) {
            $this->expectException($err);
        }
        $linkCheckerAction($original, $slug, $userId);
    }
    public function tearDown(): void
    {
        foreach(self::linkCreateProvider() as $element) {
            $link = $this->linkRepository->getBySlug($element[1]);
            if ($link) {
                $this->linkRepository->delete($link->id);
            }
        }
    }

    public static function linkCreateProvider(): array
    {
        return [
            ["https://youtube.com", "ytTEST", 2],
            ["https://github.com", "ghTEST", 2],
            ["https://google.com", "gTEST", 2],
        ];
    }

    public static function linkCheckerProvider(): array
    {
        return [
            ["https://github.com", "aaTEST", 2, LinkExistsException::class],
            ["https://github.com", "aaTEST", 3, null],
            ["https://github.com", "ghTEST", 3, LinkExistsException::class],
        ];
    }
}
