<?php

namespace Metabor\KeyValue;

use ArrayAccess;
use MetaborStd\ArrayConvertableInterface;

/**
 * @author otischlinger
 */
class Nullable implements ArrayAccess, ArrayConvertableInterface
{
    /**
     * @var array
     */
    private $keyValue = array();

    /**
     * @param array $keyValue
     */
    public function __construct(array $keyValue = array())
    {
        $this->keyValue = $keyValue;
    }

    /**
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset): bool
    {
        return isset($this->keyValue[$offset]);
    }

    /**
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset): mixed
    {
        if (isset($this->keyValue[$offset])) {
            return $this->keyValue[$offset];
        }
        return null;
    }

    /**
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value): void
    {
        $this->keyValue[$offset] = $value;
    }

    /**
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset): void
    {
        unset($this->keyValue[$offset]);
    }

    /**
     * @see \MetaborStd\ArrayConvertableInterface::toArray()
     */
    public function toArray()
    {
        return $this->keyValue;
    }
}
