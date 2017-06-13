# Enum [![CircleCI](https://circleci.com/gh/polass/enum.svg?style=svg)](https://circleci.com/gh/polass/enum)

## Usage

```php
<?php

use Polass\Enum\Enum;

class Status extends Enum
{
    /**
     * The key of default value.
     *
     * @var string
     */
    const __default = 'DISABLED';

    /***
     * A disabled status.
     *
     * @var int
     */
    const DISABLED = 0;

    /***
     * A enabled status.
     *
     * @var int
     */
    const ENABLED = 1;
}


// Create a instance with a static method with the same name as a constant.
$status = Status::ENABLED();

// Create a instance with a constant name.
$status = Status::make('ENABLED');

// Create a instance with a constant value.
$status = Status::from(1);

// Create a instance with a default.
$status = Status::default();
$status = Status::from(null, true);


// Compare a instance and a other instance.
$status->is(Status::ENABLED());

// Compare a instance and a constant name.
$status->is('ENABLED');

// Compare a instance value and a other instance value.
$status->equals(Status::ENABLED());

// Compare a instance value and a value.
$status->equals(1);


// Get constant name of instance.
$name = $status->key;

// Get constant value of instance.
$value = $status->value;

// Get the constant name with the specified value.
$name = Status::keyOf(1);

// Get the value of specified constant
$value = Status::ENABLED;
$value = Status::valueOf('ENABLED');
```
