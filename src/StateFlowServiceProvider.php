<?php

namespace Louishrg\StateFlow;

use Illuminate\Support\ServiceProvider;
use Louishrg\StateFlow\Commands\NewState;

class StateFlowServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            /*$this->commands([
                NewState::class,
            ]);*/
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {

    }
}
