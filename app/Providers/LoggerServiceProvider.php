<?php

namespace App\Providers;

use App\Models\Log;
use App\Services\Logger;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class LoggerServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        Relation::morphMap([
            'Тварина' => 'App\Models\Animal',
            'Користувач' => 'App\User',
        ]);

        $this->app->singleton('rha_logger', function ($app) {
            return new Logger(new Log());
        });
    }

}
