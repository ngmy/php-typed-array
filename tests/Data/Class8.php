<?php

declare(strict_types=1);

namespace Ngmy\TypedArray\Tests\Data;

class Class8
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
}
