<?php

namespace App\Http\Controllers;

use App\Actions\LinkCheckerAction;
use App\Exceptions\LinkExistsException;
use App\Exceptions\LinkForbidden;
use App\Exceptions\LinkNotExistException;
use App\Exceptions\LinkUnsafeException;
use App\Models\Link;
use App\Services\AdminLinkService;
use App\Services\Interfaces\ILinkService;
use App\Services\UserLinkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminController extends Controller
{
    private AdminLinkService $linkService;

    /**
     * @param AdminLinkService $linkService
     */
    public function __construct(AdminLinkService $linkService)
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
     * @param int $id
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function patch(Request $request, int $id): Response|RedirectResponse
    {
        try {
            $link = $this->linkService->get($id);
        } catch (LinkNotExistException $e) {
            abort(404, $e->getMessage());
        }

        if ($request->isMethod('get')) {
            return response('admin.patch', [
                'link' => $link
            ]);
        }

        try {
            $this->linkService->patch(id: $id, original: $request->original, slug: $request->slug);
        } catch (LinkExistsException|LinkUnsafeException $e) {
            abort(422, $e->getMessage());
        }
        return response()->redirectTo('admin.home');
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
        } catch (LinkNotExistException $e) {
            abort(404, $e->getMessage());
        }
        return redirect('home');
    }
}
