<?php

namespace App\Providers;

use App\Auth\KyivIdProvider;
use App\Services\Animals\AnimalChronicleService;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        $this->app->singleton('App\Services\Animals\AnimalChronicleServiceInterface', function ($app) {
            return new AnimalChronicleService;
        });

        Relation::morphMap([
            'Смерть' => 'App\Models\DeathArchiveRecord',
            'Виїзд' => 'App\Models\MovedOutArchiveRecord',
        ]);

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
