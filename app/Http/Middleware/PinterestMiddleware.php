<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class PinterestMiddleware
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
        if(Auth::user()->pinterest){
            Pinterest::auth()->setOAuthToken(Auth::user()->pinterest->token);
        }
        return $next($request);
    }
}
