<?php

namespace App\Http\Middleware;

use Closure;

class CheckProductCount
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
        //TODO implement it as soon as possible
        return $next($request);
    }
}
