<?php

namespace Metabor\Statemachine\Util;

/**
 * @author Oliver Tischlinger
 */
class ArrayAccessToArrayConverter
{
    private \ArrayAccess $object;

    public function __construct(\ArrayAccess $object)
    {
        $this->object = $object;
    }

    public function toArray(): array
    {
        if ($this->object instanceof \MetaborStd\ArrayConvertableInterface) {
            return $this->object->toArray();
        }
        if ($this->object instanceof \Traversable) {
            return iterator_to_array($this->object);
        }
        return [];
    }
}
