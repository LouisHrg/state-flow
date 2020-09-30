<?php

namespace Louishrg\StateFlow;

class StateStack
{
  public $states = [];
  public $key;

  public function __construct( array $states, string $key = 'key') {
    $this->states = $states;
    $this->key = $key;
  }
}
