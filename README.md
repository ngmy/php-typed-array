# PHP Typed Array
[![Latest Stable Version](https://poser.pugx.org/ngmy/typed-array/v)](//packagist.org/packages/ngmy/typed-array)
[![Total Downloads](https://poser.pugx.org/ngmy/typed-array/downloads)](//packagist.org/packages/ngmy/typed-array)
[![Latest Unstable Version](https://poser.pugx.org/ngmy/typed-array/v/unstable)](//packagist.org/packages/ngmy/typed-array)
[![License](https://poser.pugx.org/ngmy/typed-array/license)](//packagist.org/packages/ngmy/typed-array)
[![composer.lock](https://poser.pugx.org/ngmy/typed-array/composerlock)](//packagist.org/packages/ngmy/typed-array)
[![PHP CI](https://github.com/ngmy/php-typed-array/workflows/PHP%20CI/badge.svg)](https://github.com/ngmy/php-typed-array/actions?query=workflow%3A%22PHP+CI%22)
[![Coverage Status](https://coveralls.io/repos/github/ngmy/php-typed-array/badge.svg?branch=master)](https://coveralls.io/github/ngmy/php-typed-array?branch=master)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)

PHP Typed Array is the typed array for PHP.

- Can create the typed array of the array, bool, float, int, object, resource, or string type, or the specified class type, or the class type that implements the specified interface, or the class type that uses the specified trait
- Implements the `ArrayAccess`, `Countable`, and `IteratorAggregate` interfaces
- Supports the static analysis like PHPStan

```php
// Creates a new instance of a typed array of the int type
$intArray = Ngmy\TypedArray\TypedArray::ofInt();

$intArray[] = 1;      // Good
// $intArray[] = '2'; // No good. The InvalidArgumentException exception is thrown

// Creates a new instance of a typed array of the class type that implements the DateTimeInterface interface
$dateTimeInterfaceArray = Ngmy\TypedArray\TypedArray::ofInterface(DateTimeInterface::class);

$dateTimeInterfaceArray[] = new DateTime();          // Good
$dateTimeInterfaceArray[] = new DateTimeImmutable(); // Good
// $dateTimeInterfaceArray[] = new stdClass();       // No good. The InvalidArgumentException exception is thrown

foreach ($dateTimeInterfaceArray as $dateTime) {
    echo $dateTime->format('Y-m-d H:i:s') . PHP_EOL;
}

// Determines if the typed array is empty or not
echo var_export($dateTimeInterfaceArray->isEmpty(), true) . PHP_EOL; // false

// Gets the typed array of items as a plain array
print_r($dateTimeInterfaceArray->toArray());
// Array
// (
//     [0] => DateTime Object
//         ...
//     [1] => DateTimeImmutable Object
//         ...
// )
```

## Requirements
PHP Typed Array has the following requirements:

* PHP >= 7.3

## Installation
Execute the Composer `require` command:
```console
composer require ngmy/typed-array
```

## Documentation
Please see the [API documentation](https://ngmy.github.io/php-typed-array/api/).

## License
PHP Typed Array is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
