<?php

namespace App\Providers;

use App\Models\AnimalsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use View;

class ViewComposerProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @param Request $request
     * @return void
     */
    public function boot(Request $request)
    {
        View::composer('*', function ($view) {
            $view->with('auth', \Auth::user());
        });

        View::composer('admin.layout.app', function ($view) use ($request) {
            $view->with([
                'hasNewRequests' => $this->hasNewRequests($request)
            ]);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * @param Request $request
     */
    private function hasNewRequests(Request $request)
    {
        $route = $request->getRequestUri();
        if ($route) {
            if (strpos($route, '/data') === false) { // Ignoring data routes
                return (AnimalsRequest::where('processed', 0)->count() == true);
            }
        }
        return false;
    }
}
