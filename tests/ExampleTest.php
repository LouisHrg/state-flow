<?php

namespace Louishrg\LaravelStateMachine\Tests;

use Orchestra\Testbench\TestCase;
use Louishrg\LaravelStateMachine\LaravelStateMachineServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelStateMachineServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
