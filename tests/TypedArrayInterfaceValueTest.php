<?php

declare(strict_types=1);

namespace Ngmy\TypedArray\Tests;

use Exception;
use InvalidArgumentException;
use Ngmy\TypedArray\TypedArray;

class TypedArrayInterfaceValueTest extends TypedArrayPrimitiveValueTest
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
     * @param list<mixed>|null $values
     * @dataProvider dataProvider
     *
     * @phpstan-template T
     * @phpstan-param class-string<T> $valueType
     */
    public function test(string $valueType, ?array $values, Exception $exception = null): void
    {
        parent::test($valueType, $values, $exception);
    }

    /**
     * @return TypedArray<mixed, mixed>
     *
     * @phpstan-template T
     * @phpstan-param class-string<T> $valueType
     * @phpstan-return TypedArray<mixed, T>
     */
    protected function createInstance(string $valueType): TypedArray
    {
        return TypedArray::new()->withInterfaceValue($valueType);
    }
}
