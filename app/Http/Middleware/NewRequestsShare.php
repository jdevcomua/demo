<?php

namespace App\Http\Middleware;

use App\Models\AnimalsRequest;
use Closure;
use Illuminate\Support\Facades\View;

class NewRequestsShare
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
        $route = $request->route();
        if ($route) {
            if (strpos($route->getName(), '.data') === false) { // Ignoring data routes
                if (AnimalsRequest::where('processed', 0)->count()) {
                    View::share('hasNewRequests', 1);
                }
            }
        }

        return $next($request);
    }
}
