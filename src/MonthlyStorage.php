<?php

namespace MonthlyCloud\Laravel;

use Illuminate\Support\Facades\Facade;

class MonthlyStorage extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \MonthlyCloud\Sdk\StorageBuilder::class;
    }
}
