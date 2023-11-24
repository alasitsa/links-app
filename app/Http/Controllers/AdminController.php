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

    /**
     * @param ILinkService $linkService
     */
    public function __construct(ILinkService $linkService)
    {
        $this->middleware('auth');
        $this->linkService = $linkService;
    }

    /**
     * @return Response
     */
    public function getAll(): Response
    {
        $links = $this->linkService->getAll();
        return response()->view("admin.home", [
            "links" => $links,
        ]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function patch(int $id, Request $request): Response|RedirectResponse
    {
        $link = $this->linkService->get($id);
        if (!$link) {
            abort(404);
        }

        if ($request->isMethod('get')) {
            return response()->view('admin.patch', ["link" => $link]);
        }

        $original = $request->original;
        $slug = $request->slug;

        try {
            $this->linkService->patch($id, original: $original, slug: $slug);
            return redirect('admin');
        } catch (LinkExistsException|LinkUnsafeException $e) {
            abort(422, $e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse
    {
        $link = $this->linkService->get($id);
        if (!$link) {
            abort(404);
        }

        $this->linkService->delete($id);
        return redirect('admin');
    }
}
