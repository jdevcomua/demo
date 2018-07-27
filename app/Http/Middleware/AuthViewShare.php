<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;

class AuthViewShare
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
        $user = $request->user();

        View::share('auth', $user);

        return $next($request);
    }
}
