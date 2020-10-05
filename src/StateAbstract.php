<?php

namespace Louishrg\StateFlow;

use Exception;

class StateAbstract
{
    public static function __callstatic($name, $arguments)
    {
        $reflection = new static;

        if (isset($reflection->$name)) {
            if (is_array($reflection->$name) && isset($arguments[0])) {
                return $reflection->$name[$arguments[0]];
            }

            return $reflection->$name;
        }

        throw new Exception("The property ".$name." doesn't exists.");
    }
}
