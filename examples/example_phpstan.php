<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$arrayArray = Ngmy\TypedArray\TypedArray::ofArray();
$arrayArray[] = [];   // Good
$arrayArray[] = '';   // No good
$arrayArray[] = null; // No good

$boolArray = Ngmy\TypedArray\TypedArray::ofBool();
$boolArray[] = true;  // Good
$boolArray[] = false; // Good
$boolArray[] = 1;     // No good
$boolArray[] = 0;     // No good
$boolArray[] = '';    // No good
$boolArray[] = [];    // No good
$boolArray[] = null;  // No good

$floatArray = Ngmy\TypedArray\TypedArray::ofFloat();
$floatArray[] = 0.0;   // Good
$floatArray[] = '0.0'; // No good
$floatArray[] = null;  // No good

$intArray = Ngmy\TypedArray\TypedArray::ofInt();
$intArray[] = 0;    // Good
$intArray[] = '0';  // No good
$intArray[] = null; // No good

$objectArray = Ngmy\TypedArray\TypedArray::ofObject();
$objectArray[] = new stdClass(); // Good
$objectArray[] = new class() {}; // Good
$objectArray[] = '';             // No good
$objectArray[] = null;           // No good

$stringArray = Ngmy\TypedArray\TypedArray::ofString();
$stringArray[] = '0';  // Good
$stringArray[] = 0;    // No good
$stringArray[] = null; // No good

$dateTimeArray = Ngmy\TypedArray\TypedArray::ofClass(DateTime::class);
$dateTimeArray[] = new DateTime();          // Good
$dateTimeArray[] = new DateTimeImmutable(); // No good
$dateTimeArray[] = null;                    // No good

$dateTimeInterfaceArray = Ngmy\TypedArray\TypedArray::ofClass(DateTimeInterface::class);
$dateTimeInterfaceArray[] = new DateTime();          // Good
$dateTimeInterfaceArray[] = new DateTimeImmutable(); // Good
$dateTimeInterfaceArray[] = new stdClass();          // No good
$dateTimeInterfaceArray[] = null;                    // No good

$traitAArray = Ngmy\TypedArray\TypedArray::ofClass(Ngmy\TypedArray\Tests\Data\TraitA::class);
$traitAArray[] = new Ngmy\TypedArray\Tests\Data\ClassC(); // Good
$traitAArray[] = new Ngmy\TypedArray\Tests\Data\ClassA(); // No good
$traitAArray[] = null;                                    // No good

