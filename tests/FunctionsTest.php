<?php

declare(strict_types=1);

namespace Ngmy\TypedArray\Tests;

use Ngmy\TypedArray;

class FunctionsTest extends TestCase
{
    /**
     * @return array<mixed, array<mixed, mixed>>
     */
    public function classUsesRecursiveProvider(): array
    {
        return [
            [
                Data\CClass::class,
                [
                    Data\ATrait::class => Data\ATrait::class,
                ],
            ],
            [
                new Data\CClass(),
                [
                    Data\ATrait::class => Data\ATrait::class,
                ],
            ],
            [
                Data\FClass::class,
                [
                    Data\ATrait::class => Data\ATrait::class,
                    Data\BTrait::class => Data\BTrait::class,
                ],
            ],
            [
                new Data\FClass(),
                [
                    Data\ATrait::class => Data\ATrait::class,
                    Data\BTrait::class => Data\BTrait::class,
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
     * @return array<mixed, array<mixed, mixed>>
     */
    public function traitUsesRecursiveProvider(): array
    {
        return [
            [
                Data\ATrait::class,
                [],
            ],
            [
                Data\BTrait::class,
                [
                    Data\ATrait::class => Data\ATrait::class,
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
