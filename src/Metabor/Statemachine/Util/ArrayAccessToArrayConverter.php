<?php

namespace Metabor\Statemachine\Util;

use MetaborStd\ArrayConvertableInterface;
use MetaborStd\MetadataInterface;

/**
 * @author Oliver Tischlinger
 */
class ArrayAccessToArrayConverter implements ArrayConvertableInterface
{
    /**
     * @var \ArrayAccess
     */
    private $object;

    /**
     * @param \ArrayAccess $object
     */
    public function __construct(\ArrayAccess $object)
    {
        $this->object = $object;
    }

    /**
     * @see \MetaborStd\ArrayConvertableInterface::toArray()
     */
    public function toArray()
    {
        if ($this->object instanceof MetadataInterface) {
            return $this->object->getMetadata();
        } elseif ($this->object instanceof ArrayConvertableInterface) {
            return $this->object->toArray();
        } elseif ($this->object instanceof \ArrayIterator) {
            return $this->object->getArrayCopy();
        } elseif ($this->object instanceof \ArrayObject) {
            return $this->object->getArrayCopy();
        } elseif ($this->object instanceof \Traversable) {
            return iterator_to_array($this->object);
        } else {
            throw new \RuntimeException('Unable to get MetaData!');
        }
    }
}
