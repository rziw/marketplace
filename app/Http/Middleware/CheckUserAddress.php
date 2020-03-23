<?php

namespace App\Http\Middleware;

use Closure;

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
        //TODO implement it as soon as possible, that user address should not be empty for finilizing the order
        return $next($request);
    }
}
