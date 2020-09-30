<?php

namespace Louishrg\StateFlow;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Louishrg\StateFlow\Skeleton\SkeletonClass
 */
class StateFlowFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'state-flow';
    }
}
