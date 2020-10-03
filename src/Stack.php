<?php

namespace Louishrg\StateFlow;

class Stack
{
    public $states = [];
    public $key;
    public $default = null;

    public function __construct(array $states, $default = null, string $key = 'key')
    {
        $this->states = $states;
        $this->key = $key;
        $this->default = $default;
    }
}
