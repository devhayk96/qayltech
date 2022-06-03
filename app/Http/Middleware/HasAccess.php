<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HasAccess
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $resource
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next, $resource, $ttttttt)
    {
        if (!is_super_admin()) {
            return response()->json([
                'success' => false,
                'message' => 'Access Forbidden'
            ], 403);
        }
        return $next($request);
    }
}
