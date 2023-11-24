<?php

namespace App\Http\Controllers;

use App\Actions\LinkCheckerAction;
use App\Exceptions\LinkExistsException;
use App\Exceptions\LinkUnsafeException;
use App\Services\Interfaces\ILinkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    private ILinkService $linkService;
    private LinkCheckerAction $linkCheckerAction;

    /**
     * @param ILinkService $linkService
     * @param LinkCheckerAction $linkCheckerAction
     */
    public function __construct(ILinkService $linkService, LinkCheckerAction $linkCheckerAction)
    {
        $this->middleware('auth');
        $this->linkService = $linkService;
        $this->linkCheckerAction = $linkCheckerAction;
    }

    /**
     * @return Response
     */
    public function getAll(): Response
    {
        $links = $this->linkService->getByUser(auth()->user()->id);
        return response()->view("home", [
            "links" => $links,
        ]);
    }

    /**
     * @param int|null $id
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function patch(Request $request, ?int $id = null): Response|RedirectResponse {
        $link = null;
        $userId = auth()->user()->id;
        if ($id) {
            $link = $this->linkService->get($id);
            if ($link && $link->user_id != $userId) {
                abort(403);
            }
        }

        if ($request->isMethod('get')) {
            return response()->view('patch', ["link" => $link]);
        }

        $original = $request->original;
        $slug = $request->slug;

        try {
            $link
                ? $this->linkService->patch($id, original: $original, slug: $slug)
                : $this->linkService->create($userId, $original, $slug);
            return redirect('home');
        }
        catch (LinkExistsException|LinkUnsafeException $e) {
            abort(422, $e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse {
        $link = $this->linkService->get($id);
        if (!$link) {
            abort(404);
        }

        if ($link->user_id != auth()->user()->id) {
            abort(403);
        }

        $this->linkService->delete($id);
        return redirect('home');
    }
}
