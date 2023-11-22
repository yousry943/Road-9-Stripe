<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerMiddleware
{
    public function handle(Request $request, $next)
    {

        try {
            $user = JWTAuth::parseToken()->authenticate();

            if ($user->role !== 'customer') {
                return response()->json([
                    'message' => 'Unauthorized access',
                ], 403);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json([
                'message' => 'Token has expired',
            ], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([
                'message' => 'Token is invalid',
            ], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                'message' => 'Token is not provided',
            ], 401);
        }

        return $next($request);

    }
}
