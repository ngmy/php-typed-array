<?php

declare(strict_types=1);

namespace Ngmy\TypedArray\Tests;

use Ngmy\TypedArray\TypedArray;

class TypedArrayIntKeyTest extends TestCase
{
    public function testAssignValue(): void
    {
        $intKeyArray = TypedArray::new()->withIntKey();
        $intKeyArray[1] = 1;
        $intKeyArray[100] = 100;
        $intKeyArray[-1] = -1;
        $intKeyArray[] = 101;
        $intKeyArray[] = 102;

        $this->assertEquals([
            1 => 1,
            100 => 100,
            -1 => -1,
            101 => 101,
            102 => 102,
        ], $intKeyArray->toArray());
    }
}
