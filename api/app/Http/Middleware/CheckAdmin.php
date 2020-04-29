<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $User = JWTAuth::User();
        if (!$User)
            return response()->json([
                'status' => 'unauthorized',
                '$code' => 401,
                'message' => 'unauthorized',
            ], JsonResponse::HTTP_UNAUTHORIZED);

        if ($User->role_id == 1)
            return $next($request);

        return response()->json([
            'status' => 'unauthorized',
            '$code' => 401,
            'message' => 'unauthorized',
        ], JsonResponse::HTTP_UNAUTHORIZED);
    }
}
