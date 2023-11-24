<?php

namespace App\Http\Controllers\API;

use App\Exceptions\LinkExistsException;
use App\Exceptions\LinkForbidden;
use App\Exceptions\LinkNotExistException;
use App\Exceptions\LinkUnsafeException;
use App\Http\Controllers\Controller;
use App\Services\UserLinkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        return response()->json([
            "links" => $this->linkService->getAll(),
        ]);
    }

    /**
     * @param int|null $id
     * @param Request $request
     * @return JsonResponse
     */
    public function patch(Request $request, ?int $id = null): JsonResponse
    {
        if ($id) {
            try {
                $this->linkService->get($id);
            } catch (LinkNotExistException $e) {
                abort(404, $e->getMessage());
            } catch (LinkForbidden $e) {
                abort(403, $e->getMessage());
            }
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
        } catch (LinkForbidden $e) {
            abort(403, $e->getMessage());
        } catch (LinkNotExistException $e) {
            abort(404, $e->getMessage());
        }
        return response()->json("success");
    }
}
