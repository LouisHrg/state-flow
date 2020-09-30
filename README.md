# Laravel State Flow

Simple state machine / resilient states for your Laravel application !

## Installation

You can install the package via composer:

```bash
composer require louishrg/state-flow
```

## Usage


#### Create all your states class :


You need at least 1 variable : **key** which is he real value of the column in database.

If you want to use other variable as key, you can give the name of the variable in you stateStack creation (see bellow)

```php
new StateStack(self::$status, 'customKey'),
```

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

// It'll give you the state object with all your constant in it !
$user->status;
```

If you want to update/create an object with a state :

```php
$user = new User;

// Simply pass the state class and that's it !
$user->status = Pending::class;
```

#### For Laravel Nova :

Since nova will retrieve your model from the DB and cast the states to objects, you can prefix your static with an underscore to set/get the original value.

**Warning :** if you want your application to fully embrace states, you should use this attribute for this edge case only .

Example with a select :

```php
Select::make('Status','_status')
->options(
  User::getStateStack('status')
  ->pluck('label', 'key')
  ->toArray()
),
```


### Features to come :

- Complex state machine class with flow
- Awesome artisan helpers with stubs
- Validations of existing states in your model
- Possibility of using getter & settings in your state classes
- Tests

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email dev@narah.io instead of using the issue tracker.

## Credits

- [Louis Harang](https://github.com/louishrg)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
