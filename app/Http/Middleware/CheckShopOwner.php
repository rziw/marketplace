<?php

namespace App\Http\Middleware;

use Closure;

class CheckShopOwner
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
        if($request->route()->parameter('shop')->owner_id == auth('api')->id()) {
            return $next($request);
        } else {
            return response()->json(['error' => 'Shop not found'], 404);
        }
    }
}
