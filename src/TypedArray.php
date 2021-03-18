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
 * @implements ArrayAccess<mixed, mixed>
 * @implements IteratorAggregate<int|string, mixed>
 * @see https://www.php.net/manual/en/class.arrayaccess.php
 * @see https://www.php.net/manual/en/class.countable.php
 * @see https://www.php.net/manual/en/class.iteratoraggregate.php
 *
 * @phpstan-template TKey
 * @phpstan-template TValue
 * @phpstan-implements ArrayAccess<TKey, TValue>
 * @phpstan-implements IteratorAggregate<int|string, TValue>
 */
class TypedArray implements ArrayAccess, Countable, IteratorAggregate
{
    private const KEY_TYPES = [
        'bool'     => 'bool',
        'float'    => 'float',
        'int'      => 'int',
        'mixed'    => 'mixed',
        'object'   => 'object',
        'resource' => 'resource',
        'string'   => 'string',
    ];
    private const KEY_CLASS_KINDS = [
        'class'     => 'class',
        'interface' => 'interface',
        'trait'     => 'trait',
    ];
    private const VALUE_TYPES = [
        'array'    => 'array',
        'bool'     => 'bool',
        'float'    => 'float',
        'int'      => 'int',
        'mixed'    => 'mixed',
        'object'   => 'object',
        'resource' => 'resource',
        'string'   => 'string',
    ];
    private const VALUE_CLASS_KINDS = [
        'class'     => 'class',
        'interface' => 'interface',
        'trait'     => 'trait',
    ];

    /**
     * @var string
     * @phpstan-var class-string<TKey>|string
     */
    private $keyType;
    /** @var string|null */
    private $keyClassKind;
    /**
     * @var string
     * @phpstan-var class-string<TValue>|string
     */
    private $valueType;
    /** @var string|null */
    private $valueClassKind;
    /**
     * @var array<int|string, mixed>
     * @phpstan-var array<int|string, TKey>
     */
    private $keys = [];
    /**
     * @var array<int|string, mixed>
     * @phpstan-var array<int|string, TValue>
     */
    private $values = [];

    /**
     * Creates a new instance of the typed array.
     *
     * @return TypedArray<mixed, mixed>
     */
    public static function new(): self
    {
        return new self(
            self::KEY_TYPES['mixed'],
            null,
            self::KEY_TYPES['mixed'],
            null,
        );
    }

    /**
     * Returns a new instance of the typed array with the bool type key.
     *
     * @return TypedArray<bool, mixed>
     *
     * @phpstan-return TypedArray<bool, TValue>
     */
    public function withBoolKey(): self
    {
        return new self(
            self::KEY_TYPES['bool'],
            null,
            $this->valueType,
            $this->valueClassKind
        );
    }

    /**
     * Returns a new instance of the typed array with the float type key.
     *
     * @return TypedArray<float, mixed>
     *
     * @phpstan-return TypedArray<float, TValue>
     */
    public function withFloatKey(): self
    {
        return new self(
            self::KEY_TYPES['float'],
            null,
            $this->valueType,
            $this->valueClassKind
        );
    }

    /**
     * Returns a new instance of the typed array with the int type key.
     *
     * @return TypedArray<int|null, mixed>
     *
     * @phpstan-return TypedArray<int|null, TValue>
     */
    public function withIntKey(): self
    {
        return new self(
            self::KEY_TYPES['int'],
            null,
            $this->valueType,
            $this->valueClassKind
        );
    }

    /**
     * Returns a new instance of the typed array with the mixed type key.
     *
     * @return TypedArray<mixed, mixed>
     *
     * @phpstan-return TypedArray<mixed, TValue>
     */
    public function withMixedKey(): self
    {
        return new self(
            self::KEY_TYPES['mixed'],
            null,
            $this->valueType,
            $this->valueClassKind
        );
    }

    /**
     * Returns a new instance of the typed array with the object type key.
     *
     * If you are using an object as the key, it is recommended that you implement the equals()
     * and hashCode() methods on the object to determine whether keys are equal or not.
     * Otherwise, the === operator and spl_object_hash() function are used to determine it.
     * @see \Ngmy\TypedArray\Tests\Data\Class7
     *
     * @return TypedArray<object, mixed>
     *
     * @phpstan-return TypedArray<object, TValue>
     */
    public function withObjectKey(): self
    {
        return new self(
            self::KEY_TYPES['object'],
            null,
            $this->valueType,
            $this->valueClassKind
        );
    }

    /**
     * Returns a new instance of the typed array with the resource type key.
     *
     * @return TypedArray<resource, mixed>
     *
     * @phpstan-return TypedArray<resource, TValue>
     */
    public function withResourceKey(): self
    {
        return new self(
            self::KEY_TYPES['resource'],
            null,
            $this->valueType,
            $this->valueClassKind
        );
    }

    /**
     * Returns a new instance of the typed array with the string type key.
     *
     * @return TypedArray<string, mixed>
     *
     * @phpstan-return TypedArray<string, TValue>
     */
    public function withStringKey(): self
    {
        return new self(
            self::KEY_TYPES['string'],
            null,
            $this->valueType,
            $this->valueClassKind
        );
    }

    /**
     * Returns a new instance of the typed array with the specified class type key.
     *
     * If you are using an object as the key, it is recommended that you implement the equals()
     * and hashCode() methods on the object to determine whether keys are equal or not.
     * Otherwise, the === operator and spl_object_hash() function are used to determine it.
     * @see \Ngmy\TypedArray\Tests\Data\Class7
     *
     * @return TypedArray<object, mixed>
     *
     * @phpstan-template TClass
     * @phpstan-param class-string<TClass> $class
     * @phpstan-return TypedArray<TClass, TValue>
     */
    public function withClassKey(string $class): self
    {
        if (!\class_exists($class)) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The class type key must be the name of an existing class, "%s" given.',
                    $class
                )
            );
        }
        return new self(
            $class,
            self::KEY_CLASS_KINDS['class'],
            $this->valueType,
            $this->valueClassKind
        );
    }

    /**
     * Returns a new instance of the typed array with the class type key that implements the specified interface.
     *
     * If you are using an object as the key, it is recommended that you implement the equals()
     * and hashCode() methods on the object to determine whether keys are equal or not.
     * Otherwise, the === operator and spl_object_hash() function are used to determine it.
     * @see \Ngmy\TypedArray\Tests\Data\Class7
     *
     * @return TypedArray<object, mixed>
     *
     * @phpstan-template TInterface
     * @phpstan-param class-string<TInterface> $interface
     * @phpstan-return TypedArray<TInterface, TValue>
     */
    public function withInterfaceKey(string $interface): self
    {
        if (!\interface_exists($interface)) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The interface type key must be the name of an existing interface, "%s" given.',
                    $interface
                )
            );
        }
        return new self(
            $interface,
            self::KEY_CLASS_KINDS['interface'],
            $this->valueType,
            $this->valueClassKind
        );
    }

    /**
     * Returns a new instance of the typed array with the class type key that uses the specified trait.
     *
     * If you are using an object as the key, it is recommended that you implement the equals()
     * and hashCode() methods on the object to determine whether keys are equal or not.
     * Otherwise, the === operator and spl_object_hash() function are used to determine it.
     * @see \Ngmy\TypedArray\Tests\Data\Class7
     *
     * @return TypedArray<object, mixed>
     *
     * @phpstan-param class-string $trait
     */
    public function withTraitKey(string $trait): self
    {
        if (!\trait_exists($trait)) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The trait type key must be the name of an existing trait, "%s" given.',
                    $trait
                )
            );
        }
        return new self(
            $trait,
            self::KEY_CLASS_KINDS['trait'],
            $this->valueType,
            $this->valueClassKind
        );
    }

    /**
     * Returns a new instance of the typed array with the array type value.
     *
     * @return TypedArray<mixed, array<int|string, mixed>>
     *
     * @phpstan-return TypedArray<TKey, array<int|string, mixed>>
     */
    public function withArrayValue(): self
    {
        return new self(
            $this->keyType,
            $this->keyClassKind,
            self::VALUE_TYPES['array'],
            null
        );
    }

    /**
     * Returns a new instance of the typed array with the bool type value.
     *
     * @return TypedArray<mixed, bool>
     *
     * @phpstan-return TypedArray<TKey, bool>
     */
    public function withBoolValue(): self
    {
        return new self(
            $this->keyType,
            $this->keyClassKind,
            self::VALUE_TYPES['bool'],
            null
        );
    }

    /**
     * Returns a new instance of the typed array with the float type value.
     *
     * @return TypedArray<mixed, float>
     *
     * @phpstan-return TypedArray<TKey, float>
     */
    public function withFloatValue(): self
    {
        return new self(
            $this->keyType,
            $this->keyClassKind,
            self::VALUE_TYPES['float'],
            null
        );
    }

    /**
     * Returns a new instance of the typed array with the int type value.
     *
     * @return TypedArray<mixed, int>
     *
     * @phpstan-return TypedArray<TKey, int>
     */
    public function withIntValue(): self
    {
        return new self(
            $this->keyType,
            $this->keyClassKind,
            self::VALUE_TYPES['int'],
            null
        );
    }

    /**
     * Returns a new instance of the typed array with the mixed type value.
     *
     * @return TypedArray<mixed, mixed>
     *
     * @phpstan-return TypedArray<TKey, mixed>
     */
    public function withMixedValue(): self
    {
        return new self(
            $this->keyType,
            $this->keyClassKind,
            self::VALUE_TYPES['mixed'],
            null
        );
    }

    /**
     * Returns a new instance of the typed array with the object type value.
     *
     * @return TypedArray<mixed, object>
     *
     * @phpstan-return TypedArray<TKey, object>
     */
    public function withObjectValue(): self
    {
        return new self(
            $this->keyType,
            $this->keyClassKind,
            self::VALUE_TYPES['object'],
            null
        );
    }

    /**
     * Returns a new instance of the typed array with the resource type value.
     *
     * @return TypedArray<mixed, resource>
     *
     * @phpstan-return TypedArray<TKey, resource>
     */
    public function withResourceValue(): self
    {
        return new self(
            $this->keyType,
            $this->keyClassKind,
            self::VALUE_TYPES['resource'],
            null
        );
    }

    /**
     * Returns a new instance of the typed array with the string type value.
     *
     * @return TypedArray<mixed, string>
     *
     * @phpstan-return TypedArray<TKey, string>
     */
    public function withStringValue(): self
    {
        return new self(
            $this->keyType,
            $this->keyClassKind,
            self::VALUE_TYPES['string'],
            null
        );
    }

    /**
     * Returns a new instance of the typed array with the specified class type value.
     *
     * @return TypedArray<mixed, object>
     *
     * @phpstan-template TClass
     * @phpstan-param class-string<TClass> $class
     * @phpstan-return TypedArray<TKey, TClass>
     */
    public function withClassValue(string $class): self
    {
        if (!\class_exists($class)) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The class type value value be the name of an existing class, "%s" given.',
                    $class
                )
            );
        }
        return new self(
            $this->keyType,
            $this->keyClassKind,
            $class,
            self::VALUE_CLASS_KINDS['class']
        );
    }

    /**
     * Returns a new instance of the typed array with the class type value that implements the specified interface.
     *
     * @return TypedArray<mixed, object>
     *
     * @phpstan-template TInterface
     * @phpstan-param class-string<TInterface> $interface
     * @phpstan-return TypedArray<TKey, TInterface>
     */
    public function withInterfaceValue(string $interface): self
    {
        if (!\interface_exists($interface)) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The interface type value must be the name of an existing interface, "%s" given.',
                    $interface
                )
            );
        }
        return new self(
            $this->keyType,
            $this->keyClassKind,
            $interface,
            self::VALUE_CLASS_KINDS['interface']
        );
    }

    /**
     * Returns a new instance of the typed array with the class type value that uses the specified trait.
     *
     * @return TypedArray<mixed, object>
     *
     * @phpstan-param class-string $trait
     */
    public function withTraitValue(string $trait): self
    {
        if (!\trait_exists($trait)) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The trait type value must be the name of an existing trait, "%s" given.',
                    $trait
                )
            );
        }
        return new self(
            $this->keyType,
            $this->keyClassKind,
            $trait,
            self::VALUE_CLASS_KINDS['trait']
        );
    }

    /**
     * Determines if the typed array is empty or not.
     */
    public function isEmpty(): bool
    {
        return empty($this->values);
    }

    /**
     * Gets the typed array of values as a plain array.
     *
     * @return array<int|string, mixed>
     *
     * @phpstan-return array<int|string, TValue>
     */
    public function toArray(): array
    {
        return $this->values;
    }

    /**
     * @param mixed $key
     * @see https://www.php.net/manual/en/arrayaccess.offsetexists.php
     */
    public function offsetExists($key): bool
    {
        return isset($this->values[$this->getKeyHashCode($key)]);
    }

    /**
     * @param mixed $key
     * @return mixed
     * @see https://www.php.net/manual/en/arrayaccess.offsetget.php
     *
     * @phpstan-return TValue|null
     */
    public function offsetGet($key)
    {
        return $this->values[$this->getKeyHashCode($key)] ?? null;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @see https://www.php.net/manual/en/arrayaccess.offsetset.php
     *
     * @phpstan-param TKey   $key
     * @phpstan-param TValue $value
     */
    public function offsetSet($key, $value): void
    {
        if (
            ($this->valueType == self::VALUE_TYPES['array'] && !\is_array($value))
            || ($this->valueType == self::VALUE_TYPES['bool'] && !\is_bool($value))
            || ($this->valueType == self::VALUE_TYPES['float'] && !\is_float($value))
            || ($this->valueType == self::VALUE_TYPES['int'] && !\is_int($value))
            || ($this->valueType == self::VALUE_TYPES['object'] && !\is_object($value))
            || ($this->valueType == self::VALUE_TYPES['resource'] && !\is_resource($value))
            || ($this->valueType == self::VALUE_TYPES['string'] && !\is_string($value))
            || (
                $this->valueClassKind == self::VALUE_CLASS_KINDS['class']
                && !\is_a($value, $this->valueType)
            )
            || (
                $this->valueClassKind == self::VALUE_CLASS_KINDS['interface']
                && !\is_subclass_of($value, $this->valueType)
            )
            || (
                $this->valueClassKind == self::VALUE_CLASS_KINDS['trait']
                && !\array_key_exists($this->valueType, class_uses_recursive($value))
            )
        ) {
            $givenValueType = \is_object($value) ? \get_class($value) : \gettype($value);
            throw new InvalidArgumentException(
                \sprintf('The type of the value must be "%s", "%s" given.', $this->valueType, $givenValueType)
            );
        }
        $keyHashCode = $this->getKeyHashCode($key);
        if (\is_null($keyHashCode)) {
            $this->values[] = $value;
        } else {
            $this->values[$keyHashCode] = $value;
            if ($this->keyType == self::KEY_TYPES['object'] || !\is_null($this->keyClassKind)) {
                $this->keys[$keyHashCode] = $key;
            }
        }
    }

    /**
     * @param mixed $key
     * @see https://www.php.net/manual/en/arrayaccess.offsetunset.php
     */
    public function offsetUnset($key): void
    {
        $keyHashCode = $this->getKeyHashCode($key);
        unset($this->values[$keyHashCode]);
        unset($this->keys[$keyHashCode]);
    }

    /**
     * @see https://www.php.net/manual/en/countable.count.php
     */
    public function count(): int
    {
        return \count($this->values);
    }

    /**
     * @return Traversable<int|string, mixed>
     * @see https://www.php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @phpstan-return Traversable<int|string, TValue>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    private function __construct(
        string $keyType,
        ?string $keyClassKind,
        string $valueType,
        ?string $valueClassKind
    ) {
        $this->keyType = $keyType;
        $this->keyClassKind = $keyClassKind;
        $this->valueType = $valueType;
        $this->valueClassKind = $valueClassKind;
    }

    /**
     * @param mixed $key
     * @return int|string|null
     */
    private function getKeyHashCode($key)
    {
        if (
            ($this->keyType == self::KEY_TYPES['bool'] && !\is_bool($key))
            || ($this->keyType == self::KEY_TYPES['float'] && !\is_float($key))
            || ($this->keyType == self::KEY_TYPES['int'] && !\is_int($key) && !\is_null($key))
            || ($this->keyType == self::KEY_TYPES['object'] && !\is_object($key))
            || ($this->keyType == self::KEY_TYPES['resource'] && !\is_resource($key))
            || ($this->keyType == self::KEY_TYPES['string'] && !\is_string($key))
            || (
                $this->keyClassKind == self::KEY_CLASS_KINDS['class']
                && !\is_a($key, $this->keyType)
            )
            || (
                $this->keyClassKind == self::KEY_CLASS_KINDS['interface']
                && !\is_subclass_of($key, $this->keyType)
            )
            || (
                $this->keyClassKind == self::KEY_CLASS_KINDS['trait']
                && !\array_key_exists($this->keyType, class_uses_recursive($key))
            )
        ) {
            $givenKeyType = \is_object($key) ? \get_class($key) : \gettype($key);
            throw new InvalidArgumentException(
                \sprintf('The type of the key must be "%s", "%s" given.', $this->keyType, $givenKeyType)
            );
        }
        if (\is_null($key)) {
            return $key;
        }
        if (\is_float($key)) {
            return \sprintf('%.30f', $key);
        }
        if (\is_int($key)) {
            return $key;
        }
        if (\is_object($key)) {
            return \method_exists($key, 'hashCode') ? $key->hashCode() : \spl_object_hash($key);
        }
        return (string) $key;
    }
}
