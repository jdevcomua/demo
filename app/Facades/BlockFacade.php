<?php

namespace App\Facades;


use App\Entities\Block;
use Illuminate\Support\Facades\Facade;

class BlockFacade extends Facade
{
    protected static function getFacadeAccessor() { return 'block'; }
}