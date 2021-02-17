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
                Data\ClassA::class,
                [new Data\ClassA(), new Data\ClassA()],
            ],
            [
                Data\ClassA::class,
                [new Data\ClassD(), new Data\ClassD()],
            ],
            [
                Data\ClassA::class,
                [new Data\ClassA(), new Data\ClassD()],
            ],
            [
                Data\ClassA::class,
                [new Data\ClassB(), new Data\ClassB()],
                new InvalidArgumentException(),
            ],
            [
                Data\InterfaceA::class,
                [new Data\ClassB(), new Data\ClassB()],
            ],
            [
                Data\InterfaceA::class,
                [new Data\ClassA(), new Data\ClassA()],
                new InvalidArgumentException(),
            ],
            [
                Data\TraitA::class,
                [new Data\ClassC(), new Data\ClassC()],
            ],
            [
                Data\TraitA::class,
                [new Data\ClassF(), new Data\ClassF()],
            ],
            [
                Data\TraitA::class,
                [new Data\ClassC(), new Data\ClassF()],
            ],
            [
                Data\TraitA::class,
                [new Data\ClassA(), new Data\ClassA()],
                new InvalidArgumentException(),
            ],
            [
                Data\TraitB::class,
                [new Data\ClassC(), new Data\ClassC()],
                new InvalidArgumentException(),
            ],
            [
                Data\AbstractClassA::class,
                [new Data\ClassE(), new Data\ClassE()],
            ],
            [
                Data\AbstractClassA::class,
                [new Data\ClassA(), new Data\ClassA()],
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
     * @template T
     * @psalm-param class-string<T> $type
     * @param class-string<T>               $type
     * @param array<int|string, mixed>|null $items
     * @dataProvider dataProvider
     */
    public function test(string $type, ?array $items, Exception $exception = null): void
    {
        parent::test($type, $items, $exception);
    }

    /**
     * @template T
     * @psalm-param class-string<T> $type
     * @param class-string<T>               $type
     * @param array<int|string, mixed>|null $items
     * @return TypedArray<class-string<T>>
     */
    protected function createInstance(string $type, array $items = null): TypedArray
    {
        return \is_null($items)
            ? TypedArray::ofClass($type)
            : TypedArray::ofClass($type, $items);
    }
}
