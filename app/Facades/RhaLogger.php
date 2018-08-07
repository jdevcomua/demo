<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class RhaLogger extends Facade
{
    protected static function getFacadeAccessor() { return 'rha_logger'; }
}