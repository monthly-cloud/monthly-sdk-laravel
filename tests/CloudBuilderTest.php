<?php

namespace MonthlyCloud\Laravel\Test;

use MonthlyCloud;

class CloudBuilderTest extends TestCase
{
    public function test_if_storage_builder_is_implemented()
    {
        $builder = MonthlyCloud::endpoint('test');

        $this->assertStringContainsString('test', $builder->buildUrl());
    }
}
