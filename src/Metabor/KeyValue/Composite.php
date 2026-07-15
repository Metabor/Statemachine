<?php

namespace Metabor\KeyValue;

/**
 * @author Oliver Tischlinger
 */
class Composite implements \ArrayAccess
{
    private \SplObjectStorage $container;

    public function __construct()
    {
        $this->container = new \SplObjectStorage();
    }

    public function attach(\ArrayAccess $keyValue): void
    {
        $this->container->offsetSet($keyValue, null);
    }

    public function detach(\ArrayAccess $keyValue): void
    {
        $this->container->offsetUnset($keyValue);
    }

    public function offsetExists(mixed $offset): bool
    {
        if ($this->container->count()) {
            $result = true;
            /* @var $keyValue \ArrayAccess */
            foreach ($this->container as $keyValue) {
                $result = $result && $keyValue->offsetExists($offset);
            }

            return $result;
        } else {
            return false;
        }
    }

    public function offsetGet(mixed $offset): mixed
    {
        $values = [];
        /* @var $keyValue \ArrayAccess */
        foreach ($this->container as $keyValue) {
            $values[] = $keyValue->offsetGet($offset);
        }
        $values = array_unique($values, SORT_REGULAR);

        switch (count($values)) {
            case 0:
                return null;
            case 1:
                return reset($values);
            default:
                throw new \RuntimeException('Offset "' . $offset . '" is not unique!');
        }
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        /* @var $keyValue \ArrayAccess */
        foreach ($this->container as $keyValue) {
            $keyValue->offsetSet($offset, $value);
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        /* @var $keyValue \ArrayAccess */
        foreach ($this->container as $keyValue) {
            $keyValue->offsetUnset($offset);
        }
    }
}
