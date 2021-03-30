<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

$typedArray = Ngmy\TypedArray\TypedArray::new()
    ->withIntKey()
    ->withStringValue();
// NOTE: PHPStan does not yet support conditional types, so @phpstan-var is needed
/** @phpstan-var int $key */
foreach ($typedArray as $key => $value) {
    PHPStan\dumpType($key);   // int
    PHPStan\dumpType($value); // string
}

$array = $typedArray->toArray();
PHPStan\dumpType($array); // array<int|string, string>
// NOTE: PHPStan does not yet support conditional types, so @phpstan-var is needed
/** @phpstan-var array<int, string> $array */
PHPStan\dumpType($array); // array<int, string>
