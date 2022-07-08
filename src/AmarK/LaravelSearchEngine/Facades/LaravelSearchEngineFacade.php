<?php

namespace AmarK\LaravelSearchEngine\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelSearchEngineFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravelSearchEngine';
    }
}
