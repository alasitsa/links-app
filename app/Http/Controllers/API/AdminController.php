<?php

namespace App\Http\Controllers\API;

use App\Exceptions\LinkExistsException;
use App\Exceptions\LinkNotExistException;
use App\Exceptions\LinkUnsafeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LinkRequest;
use App\Services\AdminLinkService;
use Illuminate\Http\JsonResponse;

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
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        return response()->json([
            "links" => $this->linkService->getAll(),
        ]);
    }

    /**
     * @param LinkRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function patch(LinkRequest $request, int $id): JsonResponse
    {
        try {
            $this->linkService->get($id);
        } catch (LinkNotExistException $e) {
            abort(404, $e->getMessage());
        }

        try {
            $this->linkService->patch(id: $id, original: $request->original, slug: $request->slug);
        } catch (LinkExistsException|LinkUnsafeException $e) {
            abort(422, $e->getMessage());
        }
        return response()->json("success");
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $this->linkService->get($id);
            $this->linkService->delete($id);
        } catch (LinkNotExistException $e) {
            abort(404, $e->getMessage());
        }
        return response()->json("success");
    }
}
