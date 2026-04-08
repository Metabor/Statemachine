<?php

namespace Metabor\KeyValue;

use ArrayAccess;
use MetaborStd\ArrayConvertableInterface;

/**
 * @author Oliver Tischlinger
 */
class Nullable implements ArrayAccess, ArrayConvertableInterface
{
    private array $keyValue = [];

    public function __construct(array $keyValue = [])
    {
        $this->keyValue = $keyValue;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->keyValue[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        if (isset($this->keyValue[$offset])) {
            return $this->keyValue[$offset];
        }
        return null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->keyValue[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->keyValue[$offset]);
    }

    public function toArray(): array
    {
        return $this->keyValue;
    }
}
