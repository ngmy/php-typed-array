<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$arrayArray = Ngmy\TypedArray\TypedArray::ofArray();
$arrayArray[] = [];   // Good
$arrayArray[] = '';   // No good
$arrayArray[] = null; // No good
$arrayArray = Ngmy\TypedArray\TypedArray::ofArray([[]]); // Good
$arrayArray = Ngmy\TypedArray\TypedArray::ofArray(['']); // No good

$boolArray = Ngmy\TypedArray\TypedArray::ofBool();
$boolArray[] = true;  // Good
$boolArray[] = false; // Good
$boolArray[] = 1;     // No good
$boolArray[] = 0;     // No good
$boolArray[] = '';    // No good
$boolArray[] = [];    // No good
$boolArray[] = null;  // No good
$boolArray = Ngmy\TypedArray\TypedArray::ofBool([true]); // Good
$boolArray = Ngmy\TypedArray\TypedArray::ofBool([1]);    // No good

$floatArray = Ngmy\TypedArray\TypedArray::ofFloat();
$floatArray[] = 0.0;   // Good
$floatArray[] = '0.0'; // No good
$floatArray[] = null;  // No good
$floatArray = Ngmy\TypedArray\TypedArray::ofFloat([0.0]);   // Good
$floatArray = Ngmy\TypedArray\TypedArray::ofFloat(['0.0']); // No good

$intArray = Ngmy\TypedArray\TypedArray::ofInt();
$intArray[] = 0;    // Good
$intArray[] = '0';  // No good
$intArray[] = null; // No good
$intArray = Ngmy\TypedArray\TypedArray::ofInt([0]);   // Good
$intArray = Ngmy\TypedArray\TypedArray::ofInt(['0']); // No good

$objectArray = Ngmy\TypedArray\TypedArray::ofObject();
$objectArray[] = new stdClass(); // Good
$objectArray[] = new class {};   // Good
$objectArray[] = '';             // No good
$objectArray[] = null;           // No good
$objectArray = Ngmy\TypedArray\TypedArray::ofObject([new stdClass()]); // Good
$objectArray = Ngmy\TypedArray\TypedArray::ofObject(['']); // No good

$stringArray = Ngmy\TypedArray\TypedArray::ofString();
$stringArray[] = '0';  // Good
$stringArray[] = 0;    // No good
$stringArray[] = null; // No good
$stringArray = Ngmy\TypedArray\TypedArray::ofString(['0']); // Good
$stringArray = Ngmy\TypedArray\TypedArray::ofString([0]);   // No good

$dateTimeArray = Ngmy\TypedArray\TypedArray::ofClass(DateTime::class);
$dateTimeArray[] = new DateTime();          // Good
$dateTimeArray[] = new DateTimeImmutable(); // No good
$dateTimeArray[] = null;                    // No good
$dateTimeArray = Ngmy\TypedArray\TypedArray::ofClass(DateTime::class, [new DateTime()]);          // Good
$dateTimeArray = Ngmy\TypedArray\TypedArray::ofClass(DateTime::class, [new DateTimeImmutable()]); // Good. This is the false negative
$dateTimeArray = Ngmy\TypedArray\TypedArray::ofClass(DateTime::class, ['']);                      // No good

$dateTimeInterfaceArray = Ngmy\TypedArray\TypedArray::ofInterface(DateTimeInterface::class);
$dateTimeInterfaceArray[] = new DateTime();          // Good
$dateTimeInterfaceArray[] = new DateTimeImmutable(); // Good
$dateTimeInterfaceArray[] = new stdClass();          // No good
$dateTimeInterfaceArray[] = null;                    // No good
$dateTimeInterfaceArray = Ngmy\TypedArray\TypedArray::ofInterface(DateTimeInterface::class, [new DateTime()]);          // Good
$dateTimeInterfaceArray = Ngmy\TypedArray\TypedArray::ofInterface(DateTimeInterface::class, [new DateTimeImmutable()]); // Good
$dateTimeInterfaceArray = Ngmy\TypedArray\TypedArray::ofInterface(DateTimeInterface::class, ['']);                      // No good

$aTraitArray = Ngmy\TypedArray\TypedArray::ofTrait(Ngmy\TypedArray\Tests\Data\Trait1::class);
$aTraitArray[] = new Ngmy\TypedArray\Tests\Data\Class3(); // Good
$aTraitArray[] = new Ngmy\TypedArray\Tests\Data\Class1(); // Good. This is the false negative
$aTraitArray[] = null;                                    // No Good
$aTraitArray = Ngmy\TypedArray\TypedArray::ofTrait(Ngmy\TypedArray\Tests\Data\Trait1::class, [new Ngmy\TypedArray\Tests\Data\Class3()]); // Good
$aTraitArray = Ngmy\TypedArray\TypedArray::ofTrait(Ngmy\TypedArray\Tests\Data\Trait1::class, [new Ngmy\TypedArray\Tests\Data\Class1()]); // Good. This is the false negative
$aTraitArray = Ngmy\TypedArray\TypedArray::ofTrait(Ngmy\TypedArray\Tests\Data\Trait1::class, ['']);                                      // No good
