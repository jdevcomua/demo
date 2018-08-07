<?php

namespace App\Providers;

use App\Models\Log;
use App\Services\Logger;
use Illuminate\Support\ServiceProvider;

class LoggerServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->app->singleton('rha_logger', function ($app) {
            return new Logger(new Log());
        });
    }

}
