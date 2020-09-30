<?php

namespace Louishrg\StateFlow;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Exception;
use Louishrg\StateFlow\State;

use Log;

class StateCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        $stateValue = $model::findState($value, $key);

        if($stateValue) {
            return new State($stateValue);
        } else{
            throw new Exception('State not found');
        }

        throw new Exception('State doesn\'t exists.');
    }

    public function set($model, $key, $value, $attributes)
    {
        $stateKey = $model::$states[$key]->key;

        if(is_object($value)){
            return $value->key;
        }

        if(class_exists($value)) {
            $translatedKey = (new State($value))->$stateKey;
            return $translatedKey;
        }

        throw new Exception('State "'.$value.'" doesn\'t exists.');
    }
}
