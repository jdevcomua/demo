<?php

namespace App\Http\Middleware;

use Closure;

class PhoneMissing
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

        if ($user && count($user->phonesSystem) === 0)
            return redirect()->route('profile.phone-missing');

        return $next($request);
    }
}
