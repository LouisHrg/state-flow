<?php

namespace Louishrg\StateFlow\Tests;

use Orchestra\Testbench\TestCase;
use Louishrg\StateFlow\StateFlowServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [StateFlowServiceProvider::class];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
