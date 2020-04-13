<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class CheckUserAddress
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
        if (!is_null($request->user->address)) {
            return $next($request);
        }

        return response()->json(['message' => 'Address could not be empty'], 200);
    }
}
