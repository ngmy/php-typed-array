<?php

declare(strict_types=1);

namespace Ngmy\TypedArray\Tests;

use Exception;
use InvalidArgumentException;
use Ngmy\TypedArray\TypedArray;

/**
 * @group value
 */
class TypedArrayPrimitiveValueTest extends TestCase
{
    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function dataProvider(): array
    {
        return [
            [
                'array',
                [[0, 1], [0, 1]],
            ],
            [
                'array',
                [0, 1],
                new InvalidArgumentException(),
            ],
            [
                'bool',
                [true, false],
            ],
            [
                'bool',
                [0, 1],
                new InvalidArgumentException(),
            ],
            [
                'float',
                [0.0, 0.1],
            ],
            [
                'float',
                [0, 1],
                new InvalidArgumentException(),
            ],
            [
                'int',
                [0, 1],
            ],
            [
                'int',
                ['0', '1'],
                new InvalidArgumentException(),
            ],
            [
                'mixed',
                [[], true, 0.0, 0, new Data\Class1(), \tmpfile(), ''],
            ],
            [
                'object',
                [new Data\Class1(), new Data\Class2()],
            ],
            [
                'object',
                [0, 1],
                new InvalidArgumentException(),
            ],
            [
                'resource',
                [\tmpfile(), \tmpfile()],
            ],
            [
                'resource',
                [0, 1],
                new InvalidArgumentException(),
            ],
            [
                'string',
                ['0', '1'],
            ],
            [
                'string',
                [0, 1],
                new InvalidArgumentException(),
            ],
        ];
    }

    /**
     * @param list<mixed>|null $values
     * @dataProvider dataProvider
     */
    public function test(string $valueType, ?array $values, Exception $exception = null): void
    {
        if ($exception instanceof Exception) {
            $this->expectException(\get_class($exception));
        }

        \assert(\is_array($values));

        // Test instantiation
        $typedArray = $this->createInstance($valueType);

        // Test setting the value without the offset
        foreach ($values as $key => $value) {
            $typedArray[$key] = $value;
        }

        // Test calling the __clone() method
        $clonedTypedArray = clone $typedArray;
        $this->assertEquals($typedArray, $clonedTypedArray);

        // Test getting the value
        foreach ($typedArray as $key => $value) {
            $this->assertSame($values[$key], $value);
            $this->assertSame($values[$key], $typedArray[$key]);
        }

        // Test calling the count() function
        $this->assertSame(\count($values), \count($typedArray));

        // Test calling the toArray() method
        $this->assertSame($values, $typedArray->toArray());

        // Test calling the iterator_to_array() function
        $this->assertSame($values, \iterator_to_array($typedArray));

        // Test calling the unset(), isset() and empty() functions
        foreach ($typedArray as $key => $value) {
            unset($typedArray[$key]);
            $this->assertFalse(isset($typedArray[$key]));
            $this->assertTrue(empty($typedArray[$key]));
        }

        // Test calling the isEmpty() method
        $this->assertTrue($typedArray->isEmpty());

        // Test setting the value with the offset
        $typedArray = $this->createInstance($valueType);
        foreach ($values as $value) {
            $typedArray[] = $value;
        }
        $this->assertEquals($clonedTypedArray, $typedArray);
    }

    /**
     * @return TypedArray<mixed, mixed>
     */
    protected function createInstance(string $valueType): TypedArray
    {
        $withPrimitiveValue = 'with' . \ucwords(\strtolower($valueType)) . 'Value';
        return TypedArray::new()->$withPrimitiveValue();
    }
}
