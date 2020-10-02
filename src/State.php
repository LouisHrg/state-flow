<?php

namespace Louishrg\StateFlow;
use Exception;

class State
{

  private $class;
  private $flows;

  public function __construct($stateClass, $flows = null) {

      $this->class = $stateClass;

      foreach (get_class_vars($stateClass) as $key => $value) {
          $this->$key = $value;
      }

      if($flows){
        $this->flows = $flows;
      } else {
        unset($this->flows);
      }
  }

  public function canBe($target): bool
  {
    if(!isset($this->flows)) throw new Exception("Method not allowed on stacks (State::canBe)");
    return in_array($target, $this->flows[$this->class]);
  }

  public function allowedTo(): array
  {
    if(!isset($this->flows)) throw new Exception("Method not allowed on stacks (State::allowedTo)");
    return $this->flows[$this->class];
  }

  public function equal($value): bool
  {
    return $this->class = $value;
  }

  public function is(): string
  {
    return $this->class;
  }
}
