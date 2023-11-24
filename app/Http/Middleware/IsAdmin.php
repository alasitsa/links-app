<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, $type): Response
    {
        $user = auth()->user();
        if ($user && $user->hasRole('admin')) {
            return $next($request);
        }
        return $type == 'api' ?
            response()->json(['error' => 'You have not admin access'])->setStatusCode(403):
            redirect('home')->with('error','You have not admin access')->setStatusCode(403);
    }
}
