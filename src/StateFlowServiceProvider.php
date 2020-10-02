<?php

namespace Louishrg\StateFlow;

use Illuminate\Support\ServiceProvider;
use Louishrg\StateFlow\Commands\AddState;

class StateFlowServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->commands([
                AddState::class,
            ]);
        }
    }
}
