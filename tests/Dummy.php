<?php

namespace Louishrg\StateFlow\Tests;

use Illuminate\Database\Eloquent\Model;

use Louishrg\StateFlow\Casts\StateCast;
use Louishrg\StateFlow\Flow;
use Louishrg\StateFlow\Stack;
use Louishrg\StateFlow\Traits\WithState;

class Dummy extends Model
{
    use WithState;

    public static $status = [
        DummyState::class,
        NotDummyState::class,
        OkDummyState::class,
    ];

    protected $casts = [
        'dummy' => StateCast::class,
        'flow' => StateCast::class,
    ];

    protected static function registerFlow()
    {
        return (new Flow(self::$status))
      ->add(DummyState::class, [
        OkDummyState::class,
        NotDummyState::class,
      ])
      ->default(DummyState::class);
    }

    protected static function registerStates()
    {
        return [
            'dummy' => new Stack(self::$status),
            'flow' => self::registerFlow(),
        ];
    }

    protected $table = 'dummies';
    protected $guarded = [];
    public $timestamps = false;
}
