<?php

namespace App\Providers;

use App\Models\AnimalsRequest;
use App\Models\ChangeAnimalOwner;
use App\Models\FoundAnimal;
use App\Models\LostAnimal;
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
                'hasNewRequestsOwn' => $this->hasNewRequestsOwn($request),
                'hasNewRequestsLost' => $this->hasNewRequestsLost($request),
                'hasNewRequestsFound' => $this->hasNewRequestsFound($request),
                'hasNewRequestsChangeOwn' => $this->hasNewRequestsChangeOwn($request),
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
    private function hasNewRequestsOwn(Request $request)
    {
         return $this->hasNewRequestsCommon($request, AnimalsRequest::class);
    }

    private function hasNewRequestsLost(Request $request)
    {
        return $this->hasNewRequestsCommon($request, LostAnimal::class);
    }

    private function hasNewRequestsFound(Request $request)
    {
        return $this->hasNewRequestsCommon($request, FoundAnimal::class);
    }

    private function hasNewRequestsChangeOwn(Request $request)
    {
        return $this->hasNewRequestsCommon($request, ChangeAnimalOwner::class);
    }

    private function hasNewRequestsCommon(Request $request, $modelClassName)
    {
        $route = $request->getRequestUri();
        if ($route) {
            if (strpos($route, '/data') === false) { // Ignoring data routes
                return ($modelClassName::where('processed', 0)->count() == true);
            }
        }
        return false;
    }
}
