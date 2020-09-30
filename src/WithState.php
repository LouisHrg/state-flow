<?php

namespace Louishrg\StateFlow;

use Exception;
use Louishrg\StateFlow\State;
use Louishrg\StateFlow\StateProvider;

trait WithState
{
  public static $states = [];

  protected static function bootWithState()
  {
          $states = self::registerStates();
          if(!self::verifyNamespaces($states)){
            throw new Exception('Error !');
          }

          static::$states = $states;
  }

  abstract protected static function registerStates();

  protected static function verifyNamespaces(?array $states){
      foreach ($states as $namespace => $statesNamespaced) {
          if(!self::verifyState($namespace, $statesNamespaced)) return false;
      }
      return true;
  }

  public function getAttribute($key)
  {
      if (substr($key, 0, 1) === '_') {

        $originalKey = substr($key, 1);

        $key = static::$states[$originalKey]->key;

        return $this->$originalKey->$key;
      }

      return parent::getAttribute($key);
  }

  public function setAttribute($key, $value)
  {
    if (substr($key, 0, 1) === '_') {
      $originalKey = substr($key, 1);
      $translatedState = self::findState($value, $originalKey);
      $this->$originalKey = $translatedState;
      return $this;
    }
    return parent::setAttribute($key, $value);
  }


  protected static function verifyState($namespace, $stateStack){
    foreach ($stateStack->states as $state) {
      if(!class_exists($state)) return false;
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
          if($obj->$key === $value){
            return $state;
          }
      }

      return null;
  }

  public static function getStateStack(string $namespace)
  {
    $output = [];

    foreach (static::$states[$namespace]->states as $value) {
      $output[] = new State($value);
    }

    return collect($output);
  }

}
