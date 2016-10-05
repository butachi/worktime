<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Temporary extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'temporary';
    }
}
