<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

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
