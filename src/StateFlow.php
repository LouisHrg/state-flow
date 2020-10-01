<?php

namespace Louishrg\StateFlow;

class StateFlow
{
  public $states = [];
  public $flows = [];
  public $key;
  public $default;

  public function __construct( array $states, string $key = 'key') {
    $this->states = $states;
    $this->key = $key;
  }

  public function addFlow($original, $destinations)
  {
    $this->flows[$original] = $destinations;
    return $this;
  }

  public function default($class)
  {
    $this->default = $class;
    return $this;
  }
}
