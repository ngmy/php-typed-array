<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

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
