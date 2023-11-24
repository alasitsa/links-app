<?php

namespace App\Http\Controllers;

use App\Exceptions\LinkNotExistException;
use App\Services\Interfaces\ILinkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    private ILinkService $linkService;

    public function __construct(ILinkService $linkService)
    {
        $this->linkService = $linkService;
    }

    public function index(string $route): RedirectResponse {
        try {
            $original = $this->linkService->getBySlug($route);
            return response()->redirectTo($original);
        }
        catch (LinkNotExistException $e) {
            abort(404, $e->getMessage());
        }
    }
}
