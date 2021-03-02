<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new());                      // TypedArray<mixed, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withBoolKey());       // TypedArray<bool, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withFloatKey());      // TypedArray<float, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withIntKey());        // TypedArray<int, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withMixedKey());      // TypedArray<mixed, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withObjectKey());     // TypedArray<object, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withResourceKey());   // TypedArray<resource, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withStringKey());     // TypedArray<string, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()
    ->withClassKey(Ngmy\TypedArray\Tests\Data\Class1::class));            // TypedArray<Ngmy\TypedArray\Tests\Data\Class1, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()
    ->withInterfaceKey(Ngmy\TypedArray\Tests\Data\Interface1::class));    // TypedArray<Ngmy\TypedArray\Tests\Data\Interface1, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()
    ->withTraitKey(\Ngmy\TypedArray\Tests\Data\Trait1::class));           // TypedArray<object, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withArrayValue());    // TypedArray<mixed, array<int|string, mixed>>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withBoolValue());     // TypedArray<mixed, bool>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withFloatValue());    // TypedArray<mixed, float>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withIntValue());      // TypedArray<mixed, int>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withMixedValue());    // TypedArray<mixed, mixed>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withObjectValue());   // TypedArray<object, object>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withResourceValue()); // TypedArray<mixed, resource>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()->withStringValue());   // TypedArray<mixed, string>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()
    ->withClassValue(Ngmy\TypedArray\Tests\Data\Class1::class));          // TypedArray<mixed, Ngmy\TypedArray\Tests\Data\Class1>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()
    ->withInterfaceValue(Ngmy\TypedArray\Tests\Data\Interface1::class));  // TypedArray<mixed, Ngmy\TypedArray\Tests\Data\Interface1>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()
    ->withTraitValue(\Ngmy\TypedArray\Tests\Data\Trait1::class));         // TypedArray<mixed, object>
PHPStan\dumpType(Ngmy\TypedArray\TypedArray::new()
    ->withBoolKey()
    ->withArrayValue());                                                  // TypedArray<bool, array<int|string, mixed>>
