<?php

declare(strict_types=1);

namespace Ngmy\TypedArray\Tests;

use Exception;
use InvalidArgumentException;
use Ngmy\TypedArray\TypedArray;

/**
 * @group key
 */
class TypedArrayClassKeyTest extends TypedArrayPrimitiveKeyTest
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

        return [
            [
                Data\Class1::class,
                [new Data\Class1(), new Data\Class1()],
                [new Data\Class1(), new Data\Class1()],
            ],
            [
                Data\Class1::class,
                [new Data\Class4(), new Data\Class4()],
                [new Data\Class4(), new Data\Class4()],
            ],
            [
                Data\Class1::class,
                [new Data\Class1(), new Data\Class4()],
                [new Data\Class1(), new Data\Class4()],
            ],
            [
                Data\Class1::class,
                [new Data\Class2(), new Data\Class2()],
                new InvalidArgumentException(),
            ],
            [
                Data\AbstractClass1::class,
                [new Data\Class5(), new Data\Class5()],
                [new Data\Class5(), new Data\Class5()],
            ],
            [
                Data\AbstractClass1::class,
                [new Data\Class1(), new Data\Class1()],
                new InvalidArgumentException(),
            ],
            [
                Data\Interface1::class,
                [new Data\Class2(), new Data\Class2()],
                new InvalidArgumentException(),
            ],
            [
                Data\Trait1::class,
                [new Data\Class3(), new Data\Class3()],
                new InvalidArgumentException(),
            ],
            [
                Data\Class7::class,
                [new Data\Class7(1, 'John'), new Data\Class7(1, 'John')],
                [new Data\Class7(1, 'John')],
            ],
            [
                Data\Class8::class,
                [new Data\Class8(1, 'John'), new Data\Class8(1, 'John')],
                [new Data\Class8(1, 'John'), new Data\Class8(1, 'John')],
            ],
            [
                Data\Class1::class,
                [$object1, $object1],
                [$object1],
            ],
            [
                Data\Class7::class,
                [$object2, $object2],
                [$object2],
            ],
            [
                Data\Class7::class,
                [$object2, $object3],
                [$object3],
            ],
            [
                Data\Class8::class,
                [$object4, $object4],
                [$object4],
            ],
            [
                Data\Class8::class,
                [$object4, $object5],
                [$object4, $object5],
            ],
            [
                '0',
                null,
                new InvalidArgumentException(),
            ],
        ];
    }

    /**
     * @param array<int, mixed>                $keys
     * @param array<int, mixed>|Exception|null $expected
     * @dataProvider dataProvider
     *
     * @phpstan-param list<mixed>                $keys
     * @phpstan-param Exception|list<mixed>|null $expected
     */
    public function test(string $keyType, ?array $keys, $expected): void
    {
        parent::test($keyType, $keys, $expected);
    }

    /**
     * @return TypedArray<mixed, mixed>
     *
     * @phpstan-template T
     * @phpstan-param class-string<T> $keyType
     * @phpstan-return TypedArray<T, mixed>
     */
    protected function createInstance(string $keyType): TypedArray
    {
        return TypedArray::new()->withClassKey($keyType);
    }
}
