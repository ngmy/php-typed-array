<?php

declare(strict_types=1);

namespace Ngmy\TypedArray\Tests;

use Exception;
use InvalidArgumentException;
use Ngmy\TypedArray\TypedArray;

/**
 * @group key
 */
class TypedArrayInterfaceKeyTest extends TypedArrayPrimitiveKeyTest
{
    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function dataProvider(): array
    {
        return [
            [
                Data\Class1::class,
                [new Data\Class1(), new Data\Class1()],
                new InvalidArgumentException(),
            ],
            [
                Data\AbstractClass1::class,
                [new Data\Class5(), new Data\Class5()],
                new InvalidArgumentException(),
            ],
            [
                Data\Interface1::class,
                [new Data\Class2(), new Data\Class2()],
                [new Data\Class2(), new Data\Class2()],
            ],
            [
                Data\Interface1::class,
                [new Data\Class1(), new Data\Class1()],
                new InvalidArgumentException(),
            ],
            [
                Data\Trait1::class,
                [new Data\Class3(), new Data\Class3()],
                new InvalidArgumentException(),
            ],
            [
                '0',
                null,
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
        return TypedArray::new()->withInterfaceKey($keyType);
    }
}
