<?php

declare(strict_types=1);

namespace Ngmy\TypedArray\Tests\Data;

class Class7
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @param mixed $other
     */
    public function equals($other): bool
    {
        if ($other instanceof self) {
            return $other->id == $this->id;
        }
        return false;
    }

    public function hashCode(): int
    {
        return $this->id;
    }
}
