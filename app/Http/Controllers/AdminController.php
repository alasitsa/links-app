<?php

namespace App\Http\Controllers;

use App\Exceptions\LinkExistsException;
use App\Exceptions\LinkNotExistException;
use App\Exceptions\LinkUnsafeException;
use App\Http\Requests\LinkRequest;
use App\Services\AdminLinkService;
use Illuminate\Http\RedirectResponse;
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
        return response()->view("admin.home", [
            "links" => $this->linkService->getAll(),
        ]);
    }

    /**
     * @param LinkRequest $request
     * @param int $id
     * @return Response|RedirectResponse
     */
    public function patch(LinkRequest $request, int $id): Response|RedirectResponse
    {
        try {
            $link = $this->linkService->get($id);
        } catch (LinkNotExistException $e) {
            abort(404, $e->getMessage());
        }

        if ($request->isMethod('get')) {
            return response()->view('admin.patch', [
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
