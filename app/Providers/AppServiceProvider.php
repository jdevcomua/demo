<?php

namespace App\Providers;

use App\Auth\KyivIdProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootKievIDSocialite();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }

    private function bootKievIDSocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'kyivID',
            function ($app) use ($socialite) {
                $config = $app['config']['services.kyivID'];
                return $socialite->buildProvider(KyivIdProvider::class, $config);
            }
        );
    }
}
