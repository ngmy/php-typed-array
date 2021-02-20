<?php

declare(strict_types=1);

namespace Ngmy\TypedArray;

/**
 * Returns all traits used by a class, its parent classes and trait of their traits.
 *
 * @param object|string $class
 * @return array<string, string>
 * @internal
 *
 * @phpstan-param class-string|object $class
 * @phpstan-return array<class-string, class-string>
 */
function class_uses_recursive($class): array
{
    if (\is_object($class)) {
        $class = \get_class($class);
    }

    $results = [];

    \assert(\class_parents($class) !== false);
    foreach (\array_reverse(\class_parents($class)) + [$class => $class] as $class) {
        $results += trait_uses_recursive($class);
    }

    return \array_unique($results);
}

/**
 * Returns all traits used by a trait and its traits.
 *
 * @return array<string, string>
 * @internal
 *
 * @phpstan-param class-string $trait
 * @phpstan-return array<class-string, class-string>
 */
function trait_uses_recursive(string $trait): array
{
    \assert(\class_uses($trait) !== false);
    $traits = \class_uses($trait);
    /** @phpstan-var array<class-string, class-string> $traits */

    foreach ($traits as $trait) {
        $traits += trait_uses_recursive($trait);
    }

    return $traits;
}
