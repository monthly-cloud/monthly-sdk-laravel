<?php

namespace MonthlyCloud\Laravel\Test;

use MonthlyStorage;

class StorageBuilderTest extends TestCase
{
    public function test_if_storage_builder_is_implemented()
    {
        $builder = MonthlyStorage::endpoint('test');

        $this->assertStringContainsString('test', $builder->buildUrl());
    }
}
