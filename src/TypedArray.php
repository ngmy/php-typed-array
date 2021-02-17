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
 * @template T
 * @implements ArrayAccess<int|string, T>
 * @implements IteratorAggregate<int|string, T>
 * @see https://www.php.net/manual/en/class.arrayaccess.php
 * @see https://www.php.net/manual/en/class.countable.php
 * @see https://www.php.net/manual/en/class.iteratoraggregate.php
 */
class TypedArray implements ArrayAccess, Countable, IteratorAggregate
{
    private const TYPES = [
        'array'    => 'array',
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
    /** @var array<int|string, T> */
    private $items = [];

    /**
     * Create a new instance of a typed array of the array type.
     *
     * @param array<int|string, array<int|string, mixed>> $items
     * @return TypedArray<array<int|string, mixed>>
     */
    public static function ofArray(array $items = []): self
    {
        $typedArray = new self(self::TYPES['array']);
        foreach ($items as $key => $value) {
            $typedArray[$key] = $value;
        }
        return $typedArray;
    }

    /**
     * Create a new instance of a typed array of the float type.
     *
     * @param array<int|string, float> $items
     * @return TypedArray<float>
     */
    public static function ofFloat(array $items = []): self
    {
        $typedArray = new self(self::TYPES['float']);
        foreach ($items as $key => $value) {
            $typedArray[$key] = $value;
        }
        return $typedArray;
    }

    /**
     * Create a new instance of a typed array of the int type.
     *
     * @param array<int|string, int> $items
     * @return TypedArray<int>
     */
    public static function ofInt(array $items = []): self
    {
        $typedArray = new self(self::TYPES['int']);
        foreach ($items as $key => $value) {
            $typedArray[$key] = $value;
        }
        return $typedArray;
    }

    /**
     * Create a new instance of a typed array of the object type.
     *
     * @param array<int|string, object> $items
     * @return TypedArray<object>
     */
    public static function ofObject(array $items = []): self
    {
        $typedArray = new self(self::TYPES['object']);
        foreach ($items as $key => $value) {
            $typedArray[$key] = $value;
        }
        return $typedArray;
    }

    /**
     * Create a new instance of a typed array of the resource type.
     *
     * @param array<int|string, resource> $items
     * @return TypedArray<resource>
     */
    public static function ofResource(array $items = []): self
    {
        $typedArray = new self(self::TYPES['resource']);
        foreach ($items as $key => $value) {
            $typedArray[$key] = $value;
        }
        return $typedArray;
    }

    /**
     * Create a new instance of a typed array of the string type.
     *
     * @param array<int|string, string> $items
     * @return TypedArray<string>
     */
    public static function ofString(array $items = []): self
    {
        $typedArray = new self(self::TYPES['string']);
        foreach ($items as $key => $value) {
            $typedArray[$key] = $value;
        }
        return $typedArray;
    }

    /**
     * Create a new instance of a typed array of the specified class, interface, or trait type.
     *
     * @template TClass
     * @psalm-param class-string<TClass> $class
     * @param array<int|string, class-string<TClass>> $items
     * @return TypedArray<class-string<TClass>>
     */
    public static function ofClass(string $class, array $items = []): self
    {
        $typedArray = new self($class);
        $typedArray->classKind = $typedArray->determineClassKind();
        foreach ($items as $key => $value) {
            $typedArray[$key] = $value;
        }
        return $typedArray;
    }

    /**
     * Determine if the typed array is empty or not.
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Get the typed array of items as a plain array.
     *
     * @return array<int|string, T>
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
     * @return T|null
     * @see https://www.php.net/manual/en/arrayaccess.offsetget.php
     */
    public function offsetGet($key)
    {
        return $this->items[$key] ?? null;
    }

    /**
     * @param int|string|null $key
     * @param T               $value
     * @see https://www.php.net/manual/en/arrayaccess.offsetset.php
     */
    public function offsetSet($key, $value): void
    {
        if (
            ($this->type == self::TYPES['array'] && !\is_array($value))
            || ($this->type == self::TYPES['float'] && !\is_float($value))
            || ($this->type == self::TYPES['int'] && !\is_int($value))
            || ($this->type == self::TYPES['object'] && !\is_object($value))
            || ($this->type == self::TYPES['resource'] && !\is_resource($value))
            || ($this->type == self::TYPES['string'] && !\is_string($value))
            || ($this->classKind == self::CLASS_KINDS['class'] && !\is_a($value, $this->type))
            || ($this->classKind == self::CLASS_KINDS['interface'] && !\is_subclass_of($value, $this->type))
            || (
                $this->classKind == self::CLASS_KINDS['trait']
                && !\array_key_exists($this->type, class_uses_recursive($value))
            )
        ) {
            $givenType = \is_object($value) ? \get_class($value) : \gettype($value);
            throw new InvalidArgumentException(
                \sprintf('The type of items in the typed array must be "%s", "%s" given.', $this->type, $givenType)
            );
        }
        if (\is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
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
     * @return Traversable<int|string, T>
     * @see https://www.php.net/manual/en/iteratoraggregate.getiterator.php
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    private function determineClassKind(): string
    {
        if (\class_exists($this->type)) {
            return self::CLASS_KINDS['class'];
        }
        if (\interface_exists($this->type)) {
            return self::CLASS_KINDS['interface'];
        }
        if (\trait_exists($this->type)) {
            return self::CLASS_KINDS['trait'];
        }
        throw new InvalidArgumentException(
            \sprintf(
                'The type of the typed array must be the name of an existing class, interface or trait, "%s" given.',
                $this->type
            )
        );
    }
}
