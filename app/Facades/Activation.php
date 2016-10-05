<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Activation extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'activations';
    }
}
