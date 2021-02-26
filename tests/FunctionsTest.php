<?php

declare(strict_types=1);

namespace Ngmy\TypedArray\Tests;

use Ngmy\TypedArray;

class FunctionsTest extends TestCase
{
    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function classUsesRecursiveProvider(): array
    {
        return [
            [
                Data\Class3::class,
                [
                    Data\Trait1::class => Data\Trait1::class,
                ],
            ],
            [
                new Data\Class3(),
                [
                    Data\Trait1::class => Data\Trait1::class,
                ],
            ],
            [
                Data\Class6::class,
                [
                    Data\Trait1::class => Data\Trait1::class,
                    Data\Trait2::class => Data\Trait2::class,
                ],
            ],
            [
                new Data\Class6(),
                [
                    Data\Trait1::class => Data\Trait1::class,
                    Data\Trait2::class => Data\Trait2::class,
                ],
            ],
            [
                'a',
                [],
            ],
        ];
    }

    /**
     * @param object|string         $class
     * @param array<string, string> $expected
     * @dataProvider traitUsesRecursiveProvider
     *
     * @phpstan-param class-string $class
     */
    public function testClassUsesRecursive($class, array $expected): void
    {
        $actual = TypedArray\class_uses_recursive($class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function traitUsesRecursiveProvider(): array
    {
        return [
            [
                Data\Trait1::class,
                [],
            ],
            [
                Data\Trait2::class,
                [
                    Data\Trait1::class => Data\Trait1::class,
                ],
            ],
        ];
    }

    /**
     * @param array<string, string> $expected
     * @dataProvider traitUsesRecursiveProvider
     *
     * @phpstan-param class-string $trait
     */
    public function testTraitUsesRecursive(string $trait, array $expected): void
    {
        $actual = TypedArray\trait_uses_recursive($trait);

        $this->assertEquals($expected, $actual);
    }
}
