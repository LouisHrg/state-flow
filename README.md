# Laravel State Flow

Simple state machine / resilient states for your Laravel application!

## Installation

You can install the package via composer:

```bash
composer require louishrg/state-flow
```

## Simple states AKA StateStack

Simple stack that doesn't need to register transitions. If you want for example to add a hardcoded type or category to your model.

#### Create all your states class:

You need at least 1 variable: **key** which is the real value of the column in the database.

If you want to use other variables as key, you can give the name of the variable in you stateStack creation (see below)

First parameter is all your available state as an array.
Second parameter is the default value when creating a model (optional).
Third parameter is to override the default key-value.


```php
new StateStack(self::$status, Pending::class, 'customKey'),
```


Declare many states in the directory of your choice:
```php

namespace App\Models\States;

class Active
{
    public $key = 'active';

    public $label = 'Active';
    public $color = 'green';
    // and everything you want !
}

```

#### Declaration

``` php
<?php

...

use App\Models\States\Active;
use App\Models\States\Banned;
use App\Models\States\Inactive;

// Import the classes
use Louishrg\StateFlow\WithState;
use Louishrg\StateFlow\StateCast;
use Louishrg\StateFlow\StateStack;

class User extends Authenticatable
{
    // Add the trait
    use HasFactory, Notifiable, WithState;

    ...


    // store your states for a namespace in a var for example
    protected static $status = [
        Active::class,
        Banned::class,
        Inactive::class,
    ];

    // register your states as a StateStack
    protected static function registerStates(){
        return [
            'status' => new StateStack(self::$status),
        ];
    }

    ...

    // Add the cast for your column and that's it !
    protected $casts = [
        'status' => StateCast::class,
    ];
}

```

Now you can get your state as an object by typing

```php
$user = User::first();

// It'll give you the state object with all your constant in it!
$user->status;
```

If you want to update/create an object with a state:

```php
$user = new User;

// Simply pass the state class and that's it!
$user->status = Pending::class;
```

#### For Laravel Nova:

Since nova will retrieve your model from the DB and cast the states to objects, you can prefix your static with an underscore to set/get the original value.

**Warning:** if you want your application to fully embrace states, you should use this attribute for this edge case only.

Example with a select:

```php
Select::make('Status','_status')
->options(
  User::getStateStack('status')
  ->pluck('label', 'key')
  ->toArray()
),
```

## Complex State AKA StateFlow:

If you want to use states machine flows in your app you can add register like so:

```php

...

protected static function registerStates(){
    return [
        // use a custom method in your model
        'status' => self::registerNewFlow(),
    ];
}

// You need to use the StateFlow class
protected static function registerNewFlow(){
    // We'll use the data from above
    return (new StateFlow(self::$status))
    ->addFlow(Pending::class, [
        Accepted::class,
        Refused::class
    ])
    ->addFlow(Refused::class, [
        Pending::class
    ])
    ->addFlow(Accepted::class, [
        Pending::class,
        Canceled::class,
        CanceledByAdmin::class
    ])
    ->default(Pending::class);
    // You can specify a default class, when creating you don't need to provide value !
}

```

### Features to come:

- Awesome artisan helpers with stubs
- Helper to check if you can transition to another state
- Possibility of using getter & setters in your state classes
- Tests

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security-related issues, please email dev@narah.io instead of using the issue tracker.

## Credits

- [Louis Harang](https://github.com/louishrg)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
