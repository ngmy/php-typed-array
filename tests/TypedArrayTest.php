<?php

declare(strict_types=1);

namespace Ngmy\TypedArray\Tests;

use Exception;
use InvalidArgumentException;
use Ngmy\TypedArray\TypedArray;

class TypedArrayTest extends TestCase
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
                'object',
                [new Data\AClass(), new Data\BClass()],
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
     * @param array<int|string, mixed>|null $items
     * @dataProvider dataProvider
     */
    public function test(string $type, ?array $items, Exception $exception = null): void
    {
        if ($exception instanceof Exception) {
            $this->expectException(\get_class($exception));
        }

        \assert(\is_array($items));

        // Test instantiation
        $typedArray = $this->createInstance($type);

        // Test setting the value without the offset
        foreach ($items as $item) {
            $typedArray[] = $item;
        }

        // Test calling the __clone() method
        $clonedTypedArray = clone $typedArray;
        $this->assertEquals($typedArray, $clonedTypedArray);

        // Test getting the value
        foreach ($typedArray as $key => $item) {
            $this->assertSame($items[$key], $item);
            $this->assertSame($items[$key], $typedArray[$key]);
        }

        // Test calling the count() function
        $this->assertSame(\count($items), \count($typedArray));

        // Test calling the toArray() method
        $this->assertSame($items, $typedArray->toArray());

        // Test calling the iterator_to_array() function
        $this->assertSame($items, \iterator_to_array($typedArray));

        // Test calling the unset(), isset() and empty() functions
        foreach ($typedArray as $key => $item) {
            unset($typedArray[$key]);
            $this->assertFalse(isset($typedArray[$key]));
            $this->assertTrue(empty($typedArray[$key]));
        }

        // Test calling the isEmpty() method
        $this->assertTrue($typedArray->isEmpty());

        // Test setting the value with the offset
        foreach ($items as $key => $item) {
            $typedArray[$key] = $item;
        }
        $this->assertEquals($clonedTypedArray, $typedArray);

        // Test instantiation with items
        $typedArray = $this->createInstance($type, $items);
        $this->assertEquals($clonedTypedArray, $typedArray);
    }

    /**
     * @param array<int|string, mixed>|null $items
     * @return TypedArray<mixed>
     */
    protected function createInstance(string $type, array $items = null): TypedArray
    {
        $factory = 'of' . \ucwords(\strtolower($type));
        return \is_null($items)
            ? TypedArray::$factory()
            : TypedArray::$factory($items);
    }
}
