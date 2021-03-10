<?php

namespace MonthlyCloud\Laravel\Test;

use MonthlyCloud\Sdk\StorageBuilder;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            \MonthlyCloud\Laravel\MonthlyCloudServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'MonthlyCloud' => \MonthlyCloud\Laravel\MonthlyCloud::class,
            'MonthlyStorage' => \MonthlyCloud\Laravel\MonthlyStorage::class,
        ];
    }
}
