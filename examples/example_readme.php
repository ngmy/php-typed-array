<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

// Create a new instance of a typed array of the int type
$intArray = Ngmy\TypedArray\TypedArray::ofInt();

$intArray[] = 1;      // Good
// $intArray[] = '2'; // No good. The InvalidArgumentException is thrown

// Create a new instance of a typed array of the DateTimeInterface
$dateTimeInterfaceArray = Ngmy\TypedArray\TypedArray::ofClass(DateTimeInterface::class);

$dateTimeInterfaceArray[] = new DateTime();          // Good
$dateTimeInterfaceArray[] = new DateTimeImmutable(); // Good
// $dateTimeInterfaceArray[] = new stdClass();       // No good. The InvalidArgumentException is thrown

foreach ($dateTimeInterfaceArray as $dateTime) {
    echo $dateTime->format('Y-m-d H:i:s') . PHP_EOL;
}

// Determine if the typed array is empty or not
echo var_export($dateTimeInterfaceArray->isEmpty(), true) . PHP_EOL; // false

// Get the typed array of items as a plain array
print_r($dateTimeInterfaceArray->toArray());
// Array
// (
//     [0] => DateTime Object
//         ...
//     [1] => DateTimeImmutable Object
//         ...
// )
