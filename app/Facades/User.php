<?php

namespace Btcc\Facades;

use Illuminate\Support\Facades\Facade;

class User extends Facade
{
    /**
     * Create the Facade
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'User'; }
}