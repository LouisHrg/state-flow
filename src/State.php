<?php

namespace Louishrg\StateFlow;

class State
{
  public function __construct($stateClass) {
      $this->class = $stateClass;
      foreach (get_class_vars($stateClass) as $key => $value) {
          $this->$key = $value;
      }
  }
}
