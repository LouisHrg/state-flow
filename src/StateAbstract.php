<?php

namespace Louishrg\StateFlow;

use Exception;

class StateAbstract
{
    public static function __callstatic($name, $arguments){
        $reflection = new static;

        if(isset($reflection->$name)){
          return $reflection->$name;
        }

        throw new Exception("The property ".$name." doesn't exists.");
    }
}
