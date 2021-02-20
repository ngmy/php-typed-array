<?php

declare(strict_types=1);

namespace Ngmy\TypedArray\Tests;

use Exception;
use InvalidArgumentException;
use Ngmy\TypedArray\TypedArray;

class TraitArrayTest extends TypedArrayTest
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
                Data\AInterface::class,
                [new Data\BClass(), new Data\BClass()],
                new InvalidArgumentException(),
            ],
            [
                Data\AAbstractClass::class,
                [new Data\EClass(), new Data\EClass()],
                new InvalidArgumentException(),
            ],
            [
                Data\ATrait::class,
                [new Data\CClass(), new Data\CClass()],
            ],
            [
                Data\ATrait::class,
                [new Data\FClass(), new Data\FClass()],
            ],
            [
                Data\ATrait::class,
                [new Data\CClass(), new Data\FClass()],
            ],
            [
                Data\ATrait::class,
                [new Data\AClass(), new Data\AClass()],
                new InvalidArgumentException(),
            ],
            [
                Data\BTrait::class,
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
     * @phpstan-param class-string $type
     */
    protected function createInstance(string $type, array $items = null): TypedArray
    {
        return \is_null($items)
            ? TypedArray::ofTrait($type)
            : TypedArray::ofTrait($type, $items);
    }
}
