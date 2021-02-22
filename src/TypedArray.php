<?php

declare(strict_types=1);

namespace Ngmy\TypedArray;

use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

/**
 * @implements ArrayAccess<int|string, mixed>
 * @implements IteratorAggregate<int|string, mixed>
 * @see https://www.php.net/manual/en/class.arrayaccess.php
 * @see https://www.php.net/manual/en/class.countable.php
 * @see https://www.php.net/manual/en/class.iteratoraggregate.php
 *
 * @phpstan-template T
 * @phpstan-implements ArrayAccess<int|string, T>
 * @phpstan-implements IteratorAggregate<int|string, T>
 */
class TypedArray implements ArrayAccess, Countable, IteratorAggregate
{
    private const TYPES = [
        'array'    => 'array',
        'bool'     => 'bool',
        'float'    => 'float',
        'int'      => 'int',
        'object'   => 'object',
        'resource' => 'resource',
        'string'   => 'string',
    ];
    private const CLASS_KINDS = [
        'class'     => 'class',
        'interface' => 'interface',
        'trait'     => 'trait',
    ];

    /** @var string */
    private $type;
    /** @var string|null */
    private $classKind;
    /**
     * @var array<int|string, mixed>
     * @phpstan-var array<int|string, T>
     */
    private $items = [];

    /**
     * Creates a new instance of a typed array of the array type.
     *
     * @param array<int|string, array<int|string, mixed>> $items
     * @return TypedArray<array<int|string, mixed>>
     */
    public static function ofArray(array $items = []): self
    {
        $typedArray = new self(self::TYPES['array']);
        foreach ($items as $key => $item) {
            $typedArray[$key] = $item;
        }
        return $typedArray;
    }

    /**
     * Creates a new instance of a typed array of the bool type.
     *
     * @param array<int|string, bool> $items
     * @return TypedArray<bool>
     */
    public static function ofBool(array $items = []): self
    {
        $typedArray = new self(self::TYPES['bool']);
        foreach ($items as $key => $item) {
            $typedArray[$key] = $item;
        }
        return $typedArray;
    }

    /**
     * Creates a new instance of a typed array of the float type.
     *
     * @param array<int|string, float> $items
     * @return TypedArray<float>
     */
    public static function ofFloat(array $items = []): self
    {
        $typedArray = new self(self::TYPES['float']);
        foreach ($items as $key => $item) {
            $typedArray[$key] = $item;
        }
        return $typedArray;
    }

    /**
     * Creates a new instance of a typed array of the int type.
     *
     * @param array<int|string, int> $items
     * @return TypedArray<int>
     */
    public static function ofInt(array $items = []): self
    {
        $typedArray = new self(self::TYPES['int']);
        foreach ($items as $key => $item) {
            $typedArray[$key] = $item;
        }
        return $typedArray;
    }

    /**
     * Creates a new instance of a typed array of the object type.
     *
     * @param array<int|string, object> $items
     * @return TypedArray<object>
     */
    public static function ofObject(array $items = []): self
    {
        $typedArray = new self(self::TYPES['object']);
        foreach ($items as $key => $item) {
            $typedArray[$key] = $item;
        }
        return $typedArray;
    }

    /**
     * Creates a new instance of a typed array of the resource type.
     *
     * @param array<int|string, resource> $items
     * @return TypedArray<resource>
     */
    public static function ofResource(array $items = []): self
    {
        $typedArray = new self(self::TYPES['resource']);
        foreach ($items as $key => $item) {
            $typedArray[$key] = $item;
        }
        return $typedArray;
    }

    /**
     * Creates a new instance of a typed array of the string type.
     *
     * @param array<int|string, string> $items
     * @return TypedArray<string>
     */
    public static function ofString(array $items = []): self
    {
        $typedArray = new self(self::TYPES['string']);
        foreach ($items as $key => $item) {
            $typedArray[$key] = $item;
        }
        return $typedArray;
    }

    /**
     * Creates a new instance of a typed array of the specified class type.
     *
     * @param array<int|string, object> $items
     * @return TypedArray<object>
     *
     * @phpstan-template TClass
     * @phpstan-param class-string<TClass> $class
     * @phpstan-return TypedArray<TClass>
     */
    public static function ofClass(string $class, array $items = []): self
    {
        $typedArray = new self($class);
        if (!\class_exists($typedArray->type)) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The type of the typed array must be the name of an existing class, "%s" given.',
                    $typedArray->type
                )
            );
        }
        $typedArray->classKind = self::CLASS_KINDS['class'];
        foreach ($items as $key => $item) {
            $typedArray[$key] = $item;
        }
        return $typedArray;
    }

    /**
     * Creates a new instance of a typed array of the class type that implements the specified interface.
     *
     * @param array<int|string, object> $items
     * @return TypedArray<object>
     *
     * @phpstan-template TClass
     * @phpstan-param class-string<TClass> $class
     * @phpstan-return TypedArray<TClass>
     */
    public static function ofInterface(string $class, array $items = []): self
    {
        $typedArray = new self($class);
        if (!\interface_exists($typedArray->type)) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The type of the typed array must be the name of an existing interface, "%s" given.',
                    $typedArray->type
                )
            );
        }
        $typedArray->classKind = self::CLASS_KINDS['interface'];
        foreach ($items as $key => $item) {
            $typedArray[$key] = $item;
        }
        return $typedArray;
    }

    /**
     * Creates a new instance of a typed array of the class type that uses the specified trait.
     *
     * @param array<int|string, object> $items
     * @return TypedArray<object>
     *
     * @phpstan-param class-string $trait
     */
    public static function ofTrait(string $trait, array $items = []): self
    {
        $typedArray = new self($trait);
        if (!\trait_exists($typedArray->type)) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The type of the typed array must be the name of an existing trait, "%s" given.',
                    $typedArray->type
                )
            );
        }
        $typedArray->classKind = self::CLASS_KINDS['trait'];
        foreach ($items as $key => $item) {
            $typedArray[$key] = $item;
        }
        return $typedArray;
    }

    /**
     * Determines if the typed array is empty or not.
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Gets the typed array of items as a plain array.
     *
     * @return array<int|string, mixed>
     *
     * @phpstan-return array<int|string, T>
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * @param int|string|null $key
     * @see https://www.php.net/manual/en/arrayaccess.offsetexists.php
     */
    public function offsetExists($key): bool
    {
        return isset($this->items[$key]);
    }

    /**
     * @param int|string|null $key
     * @return mixed|null
     * @see https://www.php.net/manual/en/arrayaccess.offsetget.php
     *
     * @phpstan-return T|null
     */
    public function offsetGet($key)
    {
        return $this->items[$key] ?? null;
    }

    /**
     * @param int|string|null $key
     * @param mixed           $item
     * @see https://www.php.net/manual/en/arrayaccess.offsetset.php
     *
     * @phpstan-param T $item
     */
    public function offsetSet($key, $item): void
    {
        if (
            ($this->type == self::TYPES['array'] && !\is_array($item))
            || ($this->type == self::TYPES['bool'] && !\is_bool($item))
            || ($this->type == self::TYPES['float'] && !\is_float($item))
            || ($this->type == self::TYPES['int'] && !\is_int($item))
            || ($this->type == self::TYPES['object'] && !\is_object($item))
            || ($this->type == self::TYPES['resource'] && !\is_resource($item))
            || ($this->type == self::TYPES['string'] && !\is_string($item))
            || ($this->classKind == self::CLASS_KINDS['class'] && !\is_a($item, $this->type))
            || ($this->classKind == self::CLASS_KINDS['interface'] && !\is_subclass_of($item, $this->type))
            || (
                $this->classKind == self::CLASS_KINDS['trait']
                && !\array_key_exists($this->type, class_uses_recursive($item))
            )
        ) {
            $givenType = \is_object($item) ? \get_class($item) : \gettype($item);
            throw new InvalidArgumentException(
                \sprintf('The type of items in the typed array must be "%s", "%s" given.', $this->type, $givenType)
            );
        }
        if (\is_null($key)) {
            $this->items[] = $item;
        } else {
            $this->items[$key] = $item;
        }
    }

    /**
     * @param int|string|null $key
     * @see https://www.php.net/manual/en/arrayaccess.offsetunset.php
     */
    public function offsetUnset($key): void
    {
        unset($this->items[$key]);
    }

    /**
     * @see https://www.php.net/manual/en/countable.count.php
     */
    public function count(): int
    {
        return \count($this->items);
    }

    /**
     * @return Traversable<int|string, mixed>
     * @see https://www.php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @phpstan-return Traversable<int|string, T>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    private function __construct(string $type)
    {
        $this->type = $type;
    }
}
