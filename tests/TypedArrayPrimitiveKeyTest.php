<?php

declare(strict_types=1);

namespace Ngmy\TypedArray\Tests;

use Exception;
use InvalidArgumentException;
use Ngmy\TypedArray\TypedArray;

class TypedArrayPrimitiveKeyTest extends TestCase
{
    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function dataProvider(): array
    {
        $object1 = new Data\Class1();
        $object2 = new Data\Class7(1, 'John');
        $object3 = new Data\Class7(1, 'John');
        $object4 = new Data\Class8(1, 'John');
        $object5 = new Data\Class8(1, 'John');
        $resource1 = \tmpfile();
        $resource2 = \tmpfile();

        return [
            [
                'bool',
                [true, false],
                [true, false],
            ],
            [
                'bool',
                [true, true],
                [true],
            ],
            [
                'bool',
                [0, 1],
                new InvalidArgumentException(),
            ],
            [
                'float',
                [0.0, 0.1],
                [0.0, 0.1],
            ],
            [
                'float',
                [0.0, 0.0],
                [0.0],
            ],
            [
                'float',
                [0, 1],
                new InvalidArgumentException(),
            ],
            [
                'int',
                [0, 1],
                [0, 1],
            ],
            [
                'int',
                [0, 0],
                [0],
            ],
            [
                'int',
                ['0', '1'],
                new InvalidArgumentException(),
            ],
            [
                'mixed',
                [true, 0.0, 0, new Data\Class1(), $resource1, ''],
                [true, 0.0, 0, new Data\Class1(), $resource1, ''],
            ],
            [
                'mixed',
                [true, true, 0.0, 0.0, 0, 0, $object1, $object1, $resource1, $resource1, '', ''],
                [true, 0.0, 0, $object1, $resource1, ''],
            ],
            [
                'object',
                [new Data\Class1(), new Data\Class2()],
                [new Data\Class1(), new Data\Class2()],
            ],
            [
                'object',
                [new Data\Class1(), new Data\Class1()],
                [new Data\Class1(), new Data\Class1()],
            ],
            [
                'object',
                [new Data\Class7(1, 'John'), new Data\Class7(1, 'John')],
                [new Data\Class7(1, 'John')],
            ],
            [
                'object',
                [new Data\Class8(1, 'John'), new Data\Class8(1, 'John')],
                [new Data\Class8(1, 'John'), new Data\Class8(1, 'John')],
            ],
            [
                'object',
                [$object1, $object1],
                [$object1],
            ],
            [
                'object',
                [$object2, $object2],
                [$object2],
            ],
            [
                'object',
                [$object2, $object3],
                [$object3],
            ],
            [
                'object',
                [$object4, $object4],
                [$object4],
            ],
            [
                'object',
                [$object4, $object5],
                [$object4, $object5],
            ],
            [
                'object',
                [0, 1],
                new InvalidArgumentException(),
            ],
            [
                'resource',
                [$resource1, $resource2],
                [$resource1, $resource2],
            ],
            [
                'resource',
                [$resource1, $resource1],
                [$resource1],
            ],
            [
                'resource',
                [0, 1],
                new InvalidArgumentException(),
            ],
            [
                'string',
                ['0', '1'],
                ['0', '1'],
            ],
            [
                'string',
                ['0', '0'],
                ['0'],
            ],
            [
                'string',
                [0, 1],
                new InvalidArgumentException(),
            ],
        ];
    }

    /**
     * @param list<mixed>                $keys
     * @param Exception|list<mixed>|null $expected
     * @dataProvider dataProvider
     */
    public function test(string $keyType, ?array $keys, $expected): void
    {
        if ($expected instanceof Exception) {
            $this->expectException(\get_class($expected));
        }

        \assert(\is_array($keys));

        $typedArray = $this->createInstance($keyType);
        foreach ($keys as $key) {
            $typedArray[$key] = $key;
        }

        $this->assertEquals($expected, \array_values($typedArray->toArray()));
    }

    /**
     * @return TypedArray<mixed, mixed>
     */
    protected function createInstance(string $keyType): TypedArray
    {
        $withPrimitiveKey = 'with' . \ucwords(\strtolower($keyType)) . 'Key';
        return TypedArray::new()->$withPrimitiveKey();
    }
}
