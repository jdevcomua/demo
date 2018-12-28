<?php

namespace App\Providers;

use App\Models\AnimalsRequest;
use App\Models\ChangeAnimalOwner;
use App\Models\FoundAnimal;
use App\Models\LostAnimal;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;
use View;

class ViewComposerProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $view->with('auth', \Auth::user());
        });

        View::composer('admin.layout.app', function ($view) {
            $view->with([
                'hasNewRequestsOwn' => $this->hasNewRequests(AnimalsRequest::class),
                'hasNewRequestsLost' => $this->hasNewRequests(LostAnimal::class),
                'hasNewRequestsFound' => $this->hasNewRequests(FoundAnimal::class),
                'hasNewRequestsChangeOwn' => $this->hasNewRequests(ChangeAnimalOwner::class),
            ]);
        });
    }

    /**
     * @return bool|mixed
     * @throws \ReflectionException
     */
    private function hasNewRequests($class)
    {
        $name = (new ReflectionClass($class))->getShortName();
        $cachedName = $name . '_has_unprocessed';

        return \Cache::tags($name)->remember($cachedName, config('cache.ttl'), function () use ($class) {
            return !!$class::where('processed', '=', 0)->count();
        });
    }
}
