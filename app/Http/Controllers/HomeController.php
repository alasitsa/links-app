<?php

namespace App\Http\Controllers;

use App\Exceptions\LinkExistsException;
use App\Exceptions\LinkForbidden;
use App\Exceptions\LinkNotExistException;
use App\Exceptions\LinkUnsafeException;
use App\Http\Requests\LinkRequest;
use App\Services\UserLinkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    private UserLinkService $linkService;

    /**
     * @param UserLinkService $linkService
     */
    public function __construct(UserLinkService $linkService)
    {
        $this->middleware('auth');
        $this->linkService = $linkService;
    }

    /**
     * @return Response
     */
    public function getAll(): Response
    {
        return response()->view("home", [
            "links" => $this->linkService->getAll(),
        ]);
    }

    /**
     * @param LinkRequest $request
     * @param int|null $id
     * @return Response|RedirectResponse
     */
    public function patch(LinkRequest $request, ?int $id = null): Response|RedirectResponse
    {
        $link = null;
        if ($id) {
            try {
                $link = $this->linkService->get($id);
            } catch (LinkNotExistException $e) {
                abort(404, $e->getMessage());
            } catch (LinkForbidden $e) {
                abort(403, $e->getMessage());
            }
        }

        if ($request->isMethod('get')) {
            return response('patch', [
                'link' => $link
            ]);
        }

        try {
            if ($id) {
                $this->linkService->patch(id: $id, original: $request->original, slug: $request->slug);
            } else {
                $this->linkService->patch(original: $request->original, slug: $request->slug);
            }
        } catch (LinkExistsException|LinkUnsafeException $e) {
            abort(422, $e->getMessage());
        }
        return response()->redirectTo('home');
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse
    {
        try {
            $this->linkService->get($id);
            $this->linkService->delete($id);
        } catch (LinkForbidden $e) {
            abort(403, $e->getMessage());
        } catch (LinkNotExistException $e) {
            abort(404, $e->getMessage());
        }
        return redirect('home');
    }
}
