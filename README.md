# PHP Typed Array
[![Latest Stable Version](https://poser.pugx.org/ngmy/typed-array/v)](//packagist.org/packages/ngmy/typed-array)
[![Total Downloads](https://poser.pugx.org/ngmy/typed-array/downloads)](//packagist.org/packages/ngmy/typed-array)
[![Latest Unstable Version](https://poser.pugx.org/ngmy/typed-array/v/unstable)](//packagist.org/packages/ngmy/typed-array)
[![License](https://poser.pugx.org/ngmy/typed-array/license)](//packagist.org/packages/ngmy/typed-array)
[![composer.lock](https://poser.pugx.org/ngmy/typed-array/composerlock)](//packagist.org/packages/ngmy/typed-array)
[![PHP CI](https://github.com/ngmy/php-typed-array/actions/workflows/php.yml/badge.svg)](https://github.com/ngmy/php-typed-array/actions/workflows/php.yml)
[![Coverage Status](https://coveralls.io/repos/github/ngmy/php-typed-array/badge.svg?branch=master)](https://coveralls.io/github/ngmy/php-typed-array?branch=master)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)
[![Psalm Enabled](https://img.shields.io/badge/Psalm-enabled-brightgreen.svg?style=flat)](https://psalm.dev/)

PHP Typed Array is the typed array for PHP.

- Can create the typed array with the specified key and value type
- Can specify the bool, float, int, object, resource, or string type, or the specified class type, or the class type that implements the specified interface, or the class type that uses the specified trait for the key type
- Can specify the array, bool, float, int, object, resource, or string type, or the specified class type, or the class type that implements the specified interface, or the class type that uses the specified trait for the value type
- Implements the `ArrayAccess`, `Countable`, and `IteratorAggregate` interfaces
- Supports the static analysis like PHPStan and Psalm. Please see [examples](docs/examples)

```php
// Returns a new instance of the typed array with the int type value
$intArray = Ngmy\TypedArray\TypedArray::new()->withIntValue(); // TypedArray<mixed, int>

$intArray[] = 1;      // Good
// $intArray[] = '2'; // No good. The InvalidArgumentException exception is thrown

// Returns a new instance of the typed array with the class type value that implements the DateTimeInterface interface
$dateTimeInterfaceArray = Ngmy\TypedArray\TypedArray::new()
    ->withInterfaceValue(DateTimeInterface::class); // TypedArray<mixed, DateTimeInterface>

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

// You can also specify the type of the key
$stringKeyArray = Ngmy\TypedArray\TypedArray::new()->withStringKey(); // TypedArray<string, mixed>
$stringKeyArray['foo'] = 1; // Good
// $stringKeyArray[] = 2;   // No good. The InvalidArgumentException exception is thrown

// Of course, you can also specify the type of both the key and the value
$intStringArray = Ngmy\TypedArray\TypedArray::new()->withIntKey()->withStringValue(); // TypedArray<int, string>
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
