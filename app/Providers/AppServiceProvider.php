<?php

namespace App\Providers;

use App\Auth\KyivIdProvider;
use Illuminate\Support\ServiceProvider;

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

        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
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
