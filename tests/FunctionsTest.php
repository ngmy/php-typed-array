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
                Data\ClassC::class,
                [
                    Data\TraitA::class => Data\TraitA::class,
                ],
            ],
            [
                new Data\ClassC(),
                [
                    Data\TraitA::class => Data\TraitA::class,
                ],
            ],
            [
                Data\ClassF::class,
                [
                    Data\TraitA::class => Data\TraitA::class,
                    Data\TraitB::class => Data\TraitB::class,
                ],
            ],
            [
                new Data\ClassF(),
                [
                    Data\TraitA::class => Data\TraitA::class,
                    Data\TraitB::class => Data\TraitB::class,
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
                Data\TraitA::class,
                [],
            ],
            [
                Data\TraitB::class,
                [
                    Data\TraitA::class => Data\TraitA::class,
                ],
            ],
        ];
    }

    /**
     * @param array<string, string> $expected
     * @dataProvider traitUsesRecursiveProvider
     */
    public function testTraitUsesRecursive(string $trait, array $expected): void
    {
        $actual = TypedArray\trait_uses_recursive($trait);

        $this->assertEquals($expected, $actual);
    }
}
