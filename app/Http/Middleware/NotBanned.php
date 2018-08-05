<?php

namespace App\Http\Middleware;

use Closure;

class NotBanned
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

        if ($user && $user->banned)
            return redirect()->route('banned');

        return $next($request);
    }
}
