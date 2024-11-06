<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class AdminMiddleware
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
        if (Auth::user()->role=="Admin"||Auth::user()->role=="Casher"||Auth::user()->role=="Store") {
            return $next($request);
        }else{
            return response('Unauthorized!', 401);
        }
    }
}
