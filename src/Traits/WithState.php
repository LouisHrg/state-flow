<?php

namespace Louishrg\StateFlow\Traits;

use Exception;
use Louishrg\StateFlow\State;

trait WithState
{
    public static $states = [];

    protected static function bootWithState()
    {
        $states = self::registerStates();
        if (! self::verifyNamespaces($states)) {
            throw new Exception('Error !');
        }
        static::$states = $states;
    }

    abstract protected static function registerStates();

    public function __set($key, $value)
    {
        return $this->setAttributeWithState($key, $value);
    }

    public function __get($key)
    {
        return $this->getAttributeWithState($key);
    }

    public function getAttributeWithState($key)
    {
        if (substr($key, 0, 1) === '_') {
            $originalKey = substr($key, 1);
            if (isset(static::$states[$originalKey])) {
                $key = static::$states[$originalKey]->key;
                return $this->getAttribute($originalKey)->key;
            }
        }

        return parent::getAttribute($key);
    }

    public function setAttributeWithState($key, $value)
    {
        if (substr($key, 0, 1) === '_') {
            $originalKey = substr($key, 1);
            $translatedState = self::findState($value, $originalKey);
            $this->setAttribute($originalKey, $translatedState);
            return $this;
        }

        return parent::setAttribute($key, $value);
    }

    protected static function verifyNamespaces(?array $states)
    {
        foreach ($states as $namespace => $statesNamespaced) {
            if (! self::verifyState($namespace, $statesNamespaced)) {
                return false;
            }
        }

        return true;
    }

    protected static function verifyState($namespace, $stateStack)
    {
        foreach ($stateStack->states as $state) {
            if (! class_exists($state)) {
                return false;
            }
        }

        return true;
    }

    public static function findState(string $value, string $namespace): ?string
    {
        $selectArray = [];

        $states = static::$states[$namespace]->states;
        $key = static::$states[$namespace]->key;

        foreach ($states as $state) {
            $obj = new $state;
            if ($obj->$key === $value) {
                return $state;
            }
        }

        return null;
    }

    public static function findDefaultState($namespace): ?string
    {
        if (static::$states[$namespace]->default) {
            return static::$states[$namespace]->default;
        }

        return null;
    }

    public static function findFlows($namespace): ?string
    {
        if (static::$states[$namespace]->flows) {
            return static::$states[$namespace]->flows;
        }

        throw new Exception('There is no defined flows for the namespace '.$namespace);
    }


    public static function getState(string $namespace)
    {
        $output = [];

        foreach (static::$states[$namespace]->states as $value) {
            $output[] = new State($value);
        }

        return collect($output);
    }

    public function verifyStates()
    {
        foreach (self::$states as $namespace => $data) {
            $target = $this->$namespace->is();

            if (! in_array($target, $data->states)) {
                throw new Exception('Provided states isn\'t registered for "'.$namespace);
            }
        }
    }

    public function verifyTransitions()
    {
        foreach (self::$states as $namespace => $data) {
            if (! get_class($data) !== Flow::class) {
                continue;
            }

            $original = $this->original
      ? self::findState($this->original[$namespace], $namespace)
      : self::findDefaultState($namespace);

            $target = $this->$namespace->class;
            $allowedTransition = $data->flows[$original] ?? null;

            if (! $allowedTransition) {
                throw new Exception('Transition for "'.$namespace.'" not  allowed from '.$original.' to '.$target);
            }

            if ($original === $target) {
                continue;
            }

            if (! in_array($target, $allowedTransition)) {
                throw new Exception('Transition for "'.$namespace.'" not  allowed from '.$original.' to '.$target);
            }
        }
    }

    public function save(array $options = [])
    {
        $this->verifyStates();
        $this->verifyTransitions();

        parent::save($options);
    }
}
