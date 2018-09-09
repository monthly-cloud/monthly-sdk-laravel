<?php

namespace MonthlyCloud\Laravel;

use Illuminate\Support\Facades\Facade;

class MonthlyCloud extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \MonthlyCloud\Sdk\Builder::class;
    }
}
