<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new());                      // Ngmy\TypedArray\TypedArray<mixed, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withBoolKey());       // Ngmy\TypedArray\TypedArray<bool, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withFloatKey());      // Ngmy\TypedArray\TypedArray<float, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withIntKey());        // Ngmy\TypedArray\TypedArray<int, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withMixedKey());      // Ngmy\TypedArray\TypedArray<mixed, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withObjectKey());     // Ngmy\TypedArray\TypedArray<object, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withResourceKey());   // Ngmy\TypedArray\TypedArray<resource, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withStringKey());     // Ngmy\TypedArray\TypedArray<string, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()
    ->withClassKey(Ngmy\TypedArray\Tests\Data\Class1::class));            // Ngmy\TypedArray\TypedArray<Ngmy\TypedArray\Tests\Data\Class1, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()
    ->withInterfaceKey(Ngmy\TypedArray\Tests\Data\Interface1::class));    // Ngmy\TypedArray\TypedArray<Ngmy\TypedArray\Tests\Data\Interface1, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()
    ->withTraitKey(\Ngmy\TypedArray\Tests\Data\Trait1::class));           // Ngmy\TypedArray\TypedArray<object, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withArrayValue());    // Ngmy\TypedArray\TypedArray<mixed, array<int|string, mixed>>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withBoolValue());     // Ngmy\TypedArray\TypedArray<mixed, bool>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withFloatValue());    // Ngmy\TypedArray\TypedArray<mixed, float>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withIntValue());      // Ngmy\TypedArray\TypedArray<mixed, int>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withMixedValue());    // Ngmy\TypedArray\TypedArray<mixed, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withObjectValue());   // Ngmy\TypedArray\TypedArray<object, object>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withResourceValue()); // Ngmy\TypedArray\TypedArray<mixed, resource>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withStringValue());   // Ngmy\TypedArray\TypedArray<mixed, string>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()
    ->withClassValue(Ngmy\TypedArray\Tests\Data\Class1::class));          // Ngmy\TypedArray\TypedArray<mixed, Ngmy\TypedArray\Tests\Data\Class1>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()
    ->withInterfaceValue(Ngmy\TypedArray\Tests\Data\Interface1::class));  // Ngmy\TypedArray\TypedArray<mixed, Ngmy\TypedArray\Tests\Data\Interface1>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()
    ->withTraitValue(\Ngmy\TypedArray\Tests\Data\Trait1::class));         // Ngmy\TypedArray\TypedArray<mixed, object>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()
    ->withBoolKey()
    ->withArrayValue());                                                  // Ngmy\TypedArray\TypedArray<bool, array<int|string, mixed>>
