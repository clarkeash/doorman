<?php

namespace Clarkeash\Doorman\Facades;

use Illuminate\Support\Facades\Facade;

class Doorman extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'doorman';
    }
}
