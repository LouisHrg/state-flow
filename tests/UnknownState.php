<?php

namespace Louishrg\StateFlow\Tests;

use Louishrg\StateFlow\StateAbstract;

class UnknownState extends StateAbstract
{
    public $key = 'unknown';
    public $label = 'Unknown';
}
