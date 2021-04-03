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
 *
 * @psalm-template TKey
 * @psalm-template TValue
 * @template-implements ArrayAccess<TKey, TValue>
 * @template-implements IteratorAggregate<int|string, TValue>
 * @psalm-type IntArrayKey int|null
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
     * @psalm-var class-string<TKey>|string
     */
    private $keyType;
    /** @var string|null */
    private $keyClassKind;
    /**
     * @var string
     * @phpstan-var class-string<TValue>|string
     * @psalm-var class-string<TValue>|string
     */
    private $valueType;
    /** @var string|null */
    private $valueClassKind;
    /**
     * The hash map of key's hash codes and keys.
     * This is only used when the key type is the object, class, interface, or trait type.
     *
     * @var array<int|string, mixed>
     * @phpstan-var array<int|string, TKey>
     * @psalm-var array<int|string, TKey>
     */
    private $keys = [];
    /**
     * The hash map of key's hash codes and values.
     *
     * @var array<int|string, mixed>
     * @phpstan-var array<int|string, TValue>
     * @psalm-var array<int|string, TValue>
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
     *
     * @psalm-return TypedArray<bool, TValue>
     */
    public function withBoolKey(): self
    {
        /** @psalm-var TypedArray<bool, TValue> */
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
     *
     * @psalm-return TypedArray<float, TValue>
     */
    public function withFloatKey(): self
    {
        /** @psalm-var TypedArray<float, TValue> */
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
     *
     * @psalm-return TypedArray<IntArrayKey, TValue>
     */
    public function withIntKey(): self
    {
        /**
         * @phpstan-var TypedArray<int|null, TValue>
         * @psalm-var TypedArray<IntArrayKey, TValue>
         */
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
     *
     * @psalm-return TypedArray<mixed, TValue>
     */
    public function withMixedKey(): self
    {
        /** @psalm-var TypedArray<mixed, TValue> */
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
     * Otherwise, the === operator and spl_object_id() function are used to determine it.
     * @see \Ngmy\TypedArray\Tests\Data\Class7
     *
     * @return TypedArray<object, mixed>
     *
     * @phpstan-return TypedArray<object, TValue>
     *
     * @psalm-return TypedArray<object, TValue>
     */
    public function withObjectKey(): self
    {
        /** @psalm-var TypedArray<object, TValue> */
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
     *
     * @psalm-return TypedArray<resource, TValue>
     */
    public function withResourceKey(): self
    {
        /** @psalm-var TypedArray<resource, TValue> */
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
     *
     * @psalm-return TypedArray<string, TValue>
     */
    public function withStringKey(): self
    {
        /** @psalm-var TypedArray<string, TValue> */
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
     * Otherwise, the === operator and spl_object_id() function are used to determine it.
     * @see \Ngmy\TypedArray\Tests\Data\Class7
     *
     * @return TypedArray<object, mixed>
     *
     * @phpstan-template TClass
     * @phpstan-param class-string<TClass> $class
     * @phpstan-return TypedArray<TClass, TValue>
     *
     * @psalm-template TClass
     * @psalm-param class-string<TClass> $class
     * @psalm-return TypedArray<TClass, TValue>
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
        /** @psalm-var TypedArray<TClass, TValue> */
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
     * Otherwise, the === operator and spl_object_id() function are used to determine it.
     * @see \Ngmy\TypedArray\Tests\Data\Class7
     *
     * @return TypedArray<object, mixed>
     *
     * @phpstan-template TInterface
     * @phpstan-param class-string<TInterface> $interface
     * @phpstan-return TypedArray<TInterface, TValue>
     *
     * @psalm-template TInterface
     * @psalm-param class-string<TInterface> $interface
     * @psalm-return TypedArray<TInterface, TValue>
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
        /** @psalm-var TypedArray<TInterface, TValue> */
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
     * Otherwise, the === operator and spl_object_id() function are used to determine it.
     * @see \Ngmy\TypedArray\Tests\Data\Class7
     *
     * @return TypedArray<object, mixed>
     *
     * @phpstan-param class-string $trait
     * @phpstan-return TypedArray<object, TValue>
     *
     * @psalm-param trait-string $trait
     * @psalm-return TypedArray<object, TValue>
     */
    public function withTraitKey(string $trait): self
    {
        if (!\trait_exists($trait)) {
            /** @psalm-var string $trait */
            throw new InvalidArgumentException(
                \sprintf(
                    'The trait type key must be the name of an existing trait, "%s" given.',
                    $trait
                )
            );
        }
        /** @psalm-var TypedArray<object, TValue> */
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
     *
     * @psalm-return TypedArray<TKey, array<int|string, mixed>>
     */
    public function withArrayValue(): self
    {
        /** @psalm-var TypedArray<TKey, array<int|string, mixed>> */
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
     *
     * @psalm-return TypedArray<TKey, bool>
     */
    public function withBoolValue(): self
    {
        /** @psalm-var TypedArray<TKey, bool> */
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
     *
     * @psalm-return TypedArray<TKey, float>
     */
    public function withFloatValue(): self
    {
        /** @psalm-var TypedArray<TKey, float> */
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
     *
     * @psalm-return TypedArray<TKey, int>
     */
    public function withIntValue(): self
    {
        /** @psalm-var TypedArray<TKey, int> */
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
     *
     * @psalm-return TypedArray<TKey, mixed>
     */
    public function withMixedValue(): self
    {
        /** @psalm-var TypedArray<TKey, mixed> */
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
     *
     * @psalm-return TypedArray<TKey, object>
     */
    public function withObjectValue(): self
    {
        /** @psalm-var TypedArray<TKey, object> */
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
     *
     * @psalm-return TypedArray<TKey, resource>
     */
    public function withResourceValue(): self
    {
        /** @psalm-var TypedArray<TKey, resource> */
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
     *
     * @psalm-return TypedArray<TKey, string>
     */
    public function withStringValue(): self
    {
        /** @psalm-var TypedArray<TKey, string> */
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
     *
     * @psalm-template TClass
     * @psalm-param class-string<TClass> $class
     * @psalm-return TypedArray<TKey, TClass>
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
        /** @psalm-var TypedArray<TKey, TClass> */
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
     *
     * @psalm-template TInterface
     * @psalm-param class-string<TInterface> $interface
     * @psalm-return TypedArray<TKey, TInterface>
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
        /** @psalm-var TypedArray<TKey, TInterface> */
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
     * @phpstan-return TypedArray<TKey, object>
     *
     * @psalm-param trait-string $trait
     * @psalm-return TypedArray<TKey, object>
     */
    public function withTraitValue(string $trait): self
    {
        if (!\trait_exists($trait)) {
            /** @psalm-var string $trait */
            throw new InvalidArgumentException(
                \sprintf(
                    'The trait type value must be the name of an existing trait, "%s" given.',
                    $trait
                )
            );
        }
        /** @psalm-var TypedArray<TKey, object> */
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
     *
     * @psalm-return array<(
     *     TKey is IntArrayKey|object
     *         ? int
     *         : (TKey is bool|float|string|resource ? string : int|string)
     * ), TValue>
     */
    public function toArray(): array
    {
        return $this->values;
    }

    /**
     * @param mixed $key
     * @see https://www.php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @phpstan-param TKey $key
     *
     * @psalm-param TKey $key
     */
    public function offsetExists($key): bool
    {
        $keyHashCode = $this->getKeyHashCode($key);
        return $this->keyExists($key, $keyHashCode) && isset($this->values[$keyHashCode]);
    }

    /**
     * @param mixed $key
     * @return mixed
     * @see https://www.php.net/manual/en/arrayaccess.offsetget.php
     *
     * @phpstan-param TKey $key
     * @phpstan-return TValue|null
     *
     * @psalm-param TKey $key
     * @psalm-return TValue|null
     */
    public function offsetGet($key)
    {
        $keyHashCode = $this->getKeyHashCode($key);
        if (!$this->keyExists($key, $keyHashCode)) {
            return;
        }
        return $this->values[$keyHashCode] ?? null;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @see https://www.php.net/manual/en/arrayaccess.offsetset.php
     *
     * @phpstan-param TKey   $key
     * @phpstan-param TValue $value
     *
     * @psalm-param TKey   $key
     * @psalm-param TValue $value
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
                && (\is_object($value) || \is_string($value))
                && !\is_a($value, $this->valueType)
            )
            || (
                $this->valueClassKind == self::VALUE_CLASS_KINDS['interface']
                && (\is_object($value) || \is_string($value))
                && !\is_subclass_of($value, $this->valueType)
            )
            || (
                $this->valueClassKind == self::VALUE_CLASS_KINDS['trait']
                && (\is_object($value) || (\is_string($value) && \class_exists($value)))
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
            if (\is_object($key)) {
                $this->keys[$keyHashCode] = $key;
            }
        }
    }

    /**
     * @param mixed $key
     * @see https://www.php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @phpstan-param TKey $key
     *
     * @psalm-param TKey $key
     */
    public function offsetUnset($key): void
    {
        $keyHashCode = $this->getKeyHashCode($key);
        if (!$this->keyExists($key, $keyHashCode)) {
            return;
        }
        if (!isset($this->values[$keyHashCode])) {
            return;
        }
        unset($this->values[$keyHashCode]);
        if (\is_object($key)) {
            \assert(isset($this->keys[$keyHashCode]));
            unset($this->keys[$keyHashCode]);
        }
    }

    /**
     * @see https://www.php.net/manual/en/countable.count.php
     *
     * @phpstan-return 0|positive-int
     *
     * @psalm-return 0|positive-int
     */
    public function count(): int
    {
        return \count($this->values);
    }

    /**
     * @return ArrayIterator<int|string, mixed>
     * @see https://www.php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @phpstan-return ArrayIterator<int|string, TValue>
     *
     * @psalm-return ArrayIterator<(
     *     TKey is IntArrayKey|object
     *         ? int
     *         : (TKey is bool|float|string|resource ? string : int|string)
     * ), TValue>
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
     *
     * @phpstan-param TKey $key
     * @phpstan-return int|string|null
     *
     * @psalm-param TKey $key
     * @psalm-return IntArrayKey|string
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
                && (\is_object($key) || \is_string($key))
                && !\is_a($key, $this->keyType)
            )
            || (
                $this->keyClassKind == self::KEY_CLASS_KINDS['interface']
                && (\is_object($key) || \is_string($key))
                && !\is_subclass_of($key, $this->keyType)
            )
            || (
                $this->keyClassKind == self::KEY_CLASS_KINDS['trait']
                && (\is_object($key) || (\is_string($key) && \class_exists($key)))
                && !\array_key_exists($this->keyType, class_uses_recursive($key))
            )
        ) {
            $givenKeyType = \is_object($key) ? \get_class($key) : \gettype($key);
            throw new InvalidArgumentException(
                \sprintf('The type of the key must be "%s", "%s" given.', $this->keyType, $givenKeyType)
            );
        }
        if (\is_null($key)) {
            return null;
        }
        if (\is_float($key)) {
            return \sprintf('%.30f', $key);
        }
        if (\is_int($key)) {
            return $key;
        }
        if (\is_object($key)) {
            if (\method_exists($key, 'hashCode')) {
                $keyHashCode = $key->hashCode();
                \assert(\is_int($keyHashCode));
                return $keyHashCode;
            }
            return \spl_object_id($key);
        }
        return (string) $key;
    }

    /**
     * @param mixed           $key
     * @param int|string|null $keyHashCode
     *
     * @phpstan-param TKey            $key
     * @phpstan-param int|string|null $keyHashCode
     *
     * @psalm-param TKey               $key
     * @psalm-param IntArrayKey|string $keyHashCode
     */
    private function keyExists($key, $keyHashCode): bool
    {
        if (!\is_object($key)) {
            return true;
        }
        /** @psalm-var object $key */
        return isset($this->keys[$keyHashCode]) && (
            \method_exists($key, 'equals')
                ? $key->equals($this->keys[$keyHashCode])
                : $key === $this->keys[$keyHashCode]
        );
    }
}
