<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$arrayArray = Ngmy\TypedArray\TypedArray::new()->withArrayValue();
$arrayArray[] = [];   // Good
$arrayArray[] = '';   // No good
$arrayArray[] = null; // No good

$boolArray = Ngmy\TypedArray\TypedArray::new()->withBoolValue();
$boolArray[] = true;  // Good
$boolArray[] = false; // Good
$boolArray[] = 1;     // No good
$boolArray[] = 0;     // No good
$boolArray[] = '';    // No good
$boolArray[] = [];    // No good
$boolArray[] = null;  // No good

$floatArray = Ngmy\TypedArray\TypedArray::new()->withFloatValue();
$floatArray[] = 0.0;   // Good
$floatArray[] = '0.0'; // No good
$floatArray[] = null;  // No good

$intArray = Ngmy\TypedArray\TypedArray::new()->withIntValue();
$intArray[] = 0;    // Good
$intArray[] = '0';  // No good
$intArray[] = null; // No good

$objectArray = Ngmy\TypedArray\TypedArray::new()->withObjectValue();
$objectArray[] = new stdClass(); // Good
$objectArray[] = new class {};   // Good
$objectArray[] = '';             // No good
$objectArray[] = null;           // No good

$stringArray = Ngmy\TypedArray\TypedArray::new()->withStringValue();
$stringArray[] = '0';  // Good
$stringArray[] = 0;    // No good
$stringArray[] = null; // No good

$dateTimeArray = Ngmy\TypedArray\TypedArray::new()->withClassValue(DateTime::class);
$dateTimeArray[] = new DateTime();          // Good
$dateTimeArray[] = new DateTimeImmutable(); // No good
$dateTimeArray[] = null;                    // No good

$dateTimeInterfaceArray = Ngmy\TypedArray\TypedArray::new()->withInterfaceValue(DateTimeInterface::class);
$dateTimeInterfaceArray[] = new DateTime();          // Good
$dateTimeInterfaceArray[] = new DateTimeImmutable(); // Good
$dateTimeInterfaceArray[] = new stdClass();          // No good
$dateTimeInterfaceArray[] = null;                    // No good

$Trait1Array = Ngmy\TypedArray\TypedArray::new()->withTraitValue(Ngmy\TypedArray\Tests\Data\Trait1::class);
$Trait1Array[] = new Ngmy\TypedArray\Tests\Data\Class3(); // Good
$Trait1Array[] = new Ngmy\TypedArray\Tests\Data\Class1(); // Good. This is the false negative
$Trait1Array[] = null;                                    // No Good
