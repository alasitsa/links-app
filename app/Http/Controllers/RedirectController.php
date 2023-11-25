<?php

namespace App\Http\Controllers;

use App\Exceptions\LinkNotExistException;
use App\Repositories\Interfaces\ILinkRepository;
use App\Services\Interfaces\ILinkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    private ILinkRepository $linkRepository;

    public function __construct(ILinkRepository $linkRepository)
    {
        $this->linkRepository = $linkRepository;
    }

    public function index(string $route): RedirectResponse {
        $link = $this->linkRepository->getBySlug($route);
        if ($link?->original) {
            return response()->redirectTo($link->original);
        }
        abort(404);
    }
}
