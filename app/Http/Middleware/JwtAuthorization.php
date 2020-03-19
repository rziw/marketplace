<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user && in_array($user->role, $roles)) {
                return $next($request);
            }

            return response()->json(['error' => 'Forbidden, you are unauthorized to access this resource'], 403);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is not valid'], 401);
        }
    }
}
