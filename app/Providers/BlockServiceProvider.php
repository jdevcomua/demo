<?php

namespace App\Providers;

use App\Containers\BlockContainer;
use App\Entities\Block;
use Illuminate\Support\ServiceProvider;

class BlockServiceProvider extends ServiceProvider
{
    protected $defer = false;

//    /**
//     * Bootstrap services.
//     *
//     * @return void
//     */
//    public function boot()
//    {
//
//    }

    public function register()
    {
        $this->app->singleton('block', function ($app) {
            return new BlockContainer;
        });

    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [BlockContainer::class];
    }
}
