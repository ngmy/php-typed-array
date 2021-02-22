<?php

declare(strict_types=1);

namespace Ngmy\TypedArray\Tests;

use Exception;
use InvalidArgumentException;
use Ngmy\TypedArray\TypedArray;

class ClassArrayTest extends TypedArrayTest
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
            ],
            [
                Data\Class1::class,
                [new Data\Class4(), new Data\Class4()],
            ],
            [
                Data\Class1::class,
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
                '0',
                null,
                new InvalidArgumentException(),
            ],
        ];
    }

    /**
     * @param array<int|string, mixed>|null $items
     * @dataProvider dataProvider
     *
     * @phpstan-template T
     * @phpstan-param class-string<T> $type
     */
    public function test(string $type, ?array $items, Exception $exception = null): void
    {
        parent::test($type, $items, $exception);
    }

    /**
     * @param array<int|string, mixed>|null $items
     * @return TypedArray<mixed>
     *
     * @phpstan-template T
     * @phpstan-param class-string<T> $type
     * @phpstan-return TypedArray<T>
     */
    protected function createInstance(string $type, array $items = null): TypedArray
    {
        return \is_null($items)
            ? TypedArray::ofClass($type)
            : TypedArray::ofClass($type, $items);
    }
}
