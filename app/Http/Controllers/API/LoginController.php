<?php

namespace App\Http\Controllers\API;

use App\Exceptions\LoginFailed;
use App\Http\Controllers\Controller;
use App\Services\Interfaces\IUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    private IUserService $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $this->userService->login($request->email, $request->password);
            return response()->json([
                "token" => $user->createToken($request->token_name)->plainTextToken
            ]);
        }
        catch(LoginFailed $e) {
            abort(422, $e->getMessage());
        }
    }
}
