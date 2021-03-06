<?php

namespace App\Providers;

use App\Models\AnimalsFile;
use App\Models\Species;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::pattern('animal', '[0-9]+');

        parent::boot();

        Route::bind('species', function ($value) {
            return Species::where('id', $value)->first() ?? abort(404);
        });

        Route::bind('animal', function ($value, $route) {
            $animal = \Auth::user()
                ->animals
                ->where('id', '=', $value)
                ->first();
            return $animal ?? abort(404);
        });

        Route::bind('animalFile', function ($value, $route) {
            $file = AnimalsFile::where('id', '=', $value)->firstOrFail();
            $user = \Auth::user();
            if ($user) {
                if ($file->animal->user->id === $user->id) return $file;
            }
            return abort(404);
        });

    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapAdminRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    protected function mapAdminRoutes()
    {
        Route::prefix('admin')
            ->as('admin.')
            ->middleware([
                'web',
                'auth',
                'permission:admin-panel',
                'not.banned',
            ])
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }
}
