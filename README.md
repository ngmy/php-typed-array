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

- Can create the typed array of the array, float, int, object, resource, or string type, or the specified class, interface, or trait type
- Implements the `ArrayAccess`, `Countable`, and `IteratorAggregate` interface
- Supports the static analysis like PHPStan

```php
// Create a new instance of a typed array of the int type
$intArray = Ngmy\TypedArray\TypedArray::ofInt();

$intArray[] = 1; // Good
// $intArray[] = '2'; // No good. The InvalidArgumentException is thrown

// Create a new instance of a typed array of the DateTimeInterface
$dateTimeArray = Ngmy\TypedArray\TypedArray::ofClass(DateTimeInterface::class);

$dateTimeArray[] = new DateTime(); // Good
$dateTimeArray[] = new DateTimeImmutable(); // Good
// $dateTimeArray[] = new stdClass(); // No good. The InvalidArgumentException is thrown

foreach ($dateTimeArray as $dateTime) {
    var_dump($dateTime->format('Y-m-d H:i:s'));
}

// Determine if the typed array is empty or not
var_dump($dateTimeArray->isEmpty()); // bool(false)

// Get the typed array of items as a plain array
var_dump($dateTimeArray->toArray($dateTimeArray));
// array(2) {
//   [0] =>
//   class DateTime#53 (3) {
//     ...
//   }
//   [1] =>
//   class DateTimeImmutable#54 (3) {
//     ...
//   }
// }
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
