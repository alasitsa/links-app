<?php

namespace App\Http\Controllers;

use App\Actions\LinkCheckerAction;
use App\Exceptions\LinkExistsException;
use App\Exceptions\LinkUnsafeException;
use App\Models\Link;
use App\Services\Interfaces\ILinkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminController extends Controller
{
    private ILinkService $linkService;
    private LinkCheckerAction $linkCheckerAction;

    public function __construct(ILinkService $linkService, LinkCheckerAction $linkCheckerAction)
    {
        $this->middleware('auth');
        $this->linkService = $linkService;
        $this->linkCheckerAction = $linkCheckerAction;
    }

    public function getAll(): Response
    {
        $links = $this->linkService->getAll();
        return response()->view("admin.home", [
            "links" => $links,
        ]);
    }

    public function update(int $id, Request $request): Response|RedirectResponse {
        $link = $this->linkService->get($id);
        if (!$link) {
            abort(404);
        }

        if ($request->isMethod('get')) {
            return response()->view('admin.update', ["link" => $link]);
        }

        $original = $request->original;
        $slug = $request->slug;

        try {
            ($this->linkCheckerAction)($original, $slug, $link->user_id);
            $this->linkService->patch($id, original: $original, slug: $slug);
            return redirect('admin');
        }
        catch (LinkExistsException|LinkUnsafeException $e) {
            abort(422, $e->getMessage());
        }
    }

    public function delete(int $id): RedirectResponse {
        $link = $this->linkService->get($id);
        if (!$link) {
            abort(404);
        }

        $this->linkService->delete($id);
        return redirect('admin');
    }
}
