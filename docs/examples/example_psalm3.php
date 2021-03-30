<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

$typedArray = Ngmy\TypedArray\TypedArray::new()
    ->withIntKey()
    ->withStringValue();
foreach ($typedArray as $key => $value) {
    /** @psalm-trace $key */
    $key;   // int
    /** @psalm-trace $value */
    $value; // string
}

/** @psalm-trace $array */
$array = $typedArray->toArray(); // array<int, string>
print_r($array);
