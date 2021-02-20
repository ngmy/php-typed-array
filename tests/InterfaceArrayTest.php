<?php

declare(strict_types=1);

namespace Ngmy\TypedArray\Tests;

use Exception;
use InvalidArgumentException;
use Ngmy\TypedArray\TypedArray;

class InterfaceArrayTest extends TypedArrayTest
{
    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function dataProvider(): array
    {
        return [
            [
                Data\AClass::class,
                [new Data\AClass(), new Data\AClass()],
                new InvalidArgumentException(),
            ],
            [
                Data\AAbstractClass::class,
                [new Data\EClass(), new Data\EClass()],
                new InvalidArgumentException(),
            ],
            [
                Data\AInterface::class,
                [new Data\BClass(), new Data\BClass()],
            ],
            [
                Data\AInterface::class,
                [new Data\AClass(), new Data\AClass()],
                new InvalidArgumentException(),
            ],
            [
                Data\ATrait::class,
                [new Data\CClass(), new Data\CClass()],
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
            ? TypedArray::ofInterface($type)
            : TypedArray::ofInterface($type, $items);
    }
}
