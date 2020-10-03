# Laravel State Flow

[![Latest Version on Packagist](https://img.shields.io/packagist/v/louishrg/state-flow.svg?style=flat-square)](https://packagist.org/packages/louishrg/state-flow)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/louishrg/state-flow/run-tests?label=tests)](https://github.com/louishrg/state-flow/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/louishrg/state-flow.svg?style=flat-square)](https://packagist.org/packages/louishrg/state-flow)


Simple state machine / resilient states for your Laravel application!

## Installation

You can install the package via composer:

```bash
composer require louishrg/state-flow
```

## Create your states classes with artisan :

Creating files for every available states is repetitive, that's why this package provide an artisan command to speed up the process :


```bash
php artisan states:new
```

## Simple states AKA Stack

A Stack is a simple state machine that doesn't need to register transitions. It's a very convenient way to add a hardcoded type or category to your model.

#### How to use :

You need at least 1 variable: **key** which is the real value of the column in the database.

If you want to use other variables as key, you can give the name of the variable in you stateStack creation (see below)

- First parameter is all your available state as an array.
- Second parameter is the default value when creating a model (optional).
- Third parameter is to override the default key-value (optional).


```php
new Stack(self::$status, Pending::class, 'key'),
```

Declare your states classes in the directory of your choice, for example :

```php
namespace App\Models\States\User;

class Active
{
    public $key = 'active';

    public $label = 'Active';
    public $color = 'green';
    // and everything you want !
}
```

Now, add all the needed declaration in your model :

``` php
<?php

...

// Import all your states
use App\Models\States\Active;
use App\Models\States\Banned;
use App\Models\States\Inactive;

// Import the classes
use Louishrg\StateFlow\Traits\WithState;
use Louishrg\StateFlow\Casts\StateCast;
use Louishrg\StateFlow\Stack;

class User
{
    // Add WithState trait
    use WithState, ...;

    ...

    // You can register all available states for a namespace in a var for example
    protected static $status = [
        Active::class,
        Banned::class,
        Inactive::class,
    ];

    // register your states as a Stack for the namespace "status"
    protected static function registerStates(){
        return [
            'status' => new Stack(self::$status),
        ];
    }

    ...

    // Add the cast for your column and that's it !
    protected $casts = [
        'status' => StateCast::class,
    ];
}

```

Now you can get your state like so :

```php
$user->status;
// It'll give you the state object with all your defined constants in it.
```

If you want to update/create an object with a state:

```php
$user = new User;

// Simply pass the state class and that's it.
$user->status = Pending::class;
```

#### Workaround for Laravel Nova:

Since Nova will retrieve your model from the DB and cast your model states to object, you can prefix your namespace with an underscore to set/get the original value.

**Warning:** if you want your application to fully embrace this pattern, you should use this attribute for this edge case **only**.

Example with a select:

```php
Select::make('Status','_status')
->options(
  User::getStateStack('status')
  ->pluck('label', 'key')
  ->toArray()
),
```

#### Useful Methods :

If you want to compare the current value of a state with another one, you can use

```php
$user->status->equal(Banned::class);
```

Also, you can directly get the class of your current state :

```php
$user->status->is();
```

## Complex States AKA Flow:

If you want to use the real state machine pattern in your app you can add register like so:

```php

// Import the Flow class
use Louishrg\StateFlow\Flow;

...

protected static function registerStates(){
    return [
        // use a custom method in your model for better readability
        'status' => self::myFlow(),
    ];
}

// You need to use the Flow class
protected static function myFlow(){
    // We'll use the data from above
    return (new Flow(self::$status))
    // Add a transition, here your state can go from Pending to either Accepted or Refused.
    ->add(Pending::class, [
        Accepted::class,
        Refused::class
    ])
    ->add(Refused::class, [
        Pending::class
    ])
    ->add(Accepted::class, [
        Pending::class,
        Canceled::class,
        CanceledByAdmin::class
    ])
    ->default(Pending::class);
    // You can specify a default class, when creating you don't need to provide value.
}
```

#### Methods for flows :

When using flows, you can check if you can transition to another state like so :

```php
$user->status->canBe(Banned::class);
```

Or you can get all the possible transitions for your current state :

```php
$user->status->allowedTo();
```

### Features to come:

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
