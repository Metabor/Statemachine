<?php
namespace Metabor\KeyValue;

/**
 * @author otischlinger
 *
 */
class Composite implements \ArrayAccess
{
    /**
     * @var \SplObjectStorage
     */
    private $container;

    /**
     *
     */
    public function __construct()
    {
        $this->container = new \SplObjectStorage();
    }

    /**
     * @param \ArrayAccess $keyValue
     */
    public function attach(\ArrayAccess $keyValue)
    {
        $this->container->attach($keyValue);
    }

    /**
     * @param \ArrayAccess $keyValue
     */
    public function detach(\ArrayAccess $keyValue)
    {
        $this->container->detach($keyValue);
    }

    /**
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
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

    /**
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        $values = array();
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

    /**
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        /* @var $keyValue \ArrayAccess */
        foreach ($this->container as $keyValue) {
            $keyValue->offsetSet($offset, $value);
        }
    }
    /**
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        /* @var $keyValue \ArrayAccess */
        foreach ($this->container as $keyValue) {
            $keyValue->offsetUnset($offset);
        }
    }
}
