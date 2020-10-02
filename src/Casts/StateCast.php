<?php

namespace Louishrg\StateFlow\Casts;

use Exception;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Louishrg\StateFlow\State;

class StateCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        $stateValue = $value
        ? $model::findState($value, $key)
        : $model::findDefaultState($key);

        if ($stateValue) {
            $flows = isset($model::$states[$key]->flows)
            ? $model::$states[$key]->flows
            : null;

            return new State($stateValue, $flows);
        } else {
            throw new Exception("Provided State doesn't exist.");
        }

        throw new Exception("Provided State doesn't exist and no default value is provided");
    }

    public function set($model, $key, $value, $attributes)
    {
        $stateKey = $model::$states[$key]->key;

        if (is_object($value)) {
            return $value->key;
        }

        if (class_exists($value)) {
            $translatedKey = (new State($value))->$stateKey;

            return $translatedKey;
        }

        throw new Exception('State "'.$value.'" doesn\'t exists.');
    }
}
