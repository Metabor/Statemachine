<?php
namespace Metabor\KeyValue;
use ArrayAccess;

/**
 * @author otischlinger
 *
 */
class Nullable implements ArrayAccess
{
    /**
     *
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
    public function offsetExists($offset)
    {
        return isset($this->keyValue[$offset]);
    }

    /**
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        if (isset($this->keyValue[$offset])) {
            return $this->keyValue[$offset];
        }
    }

    /**
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        $this->keyValue[$offset] = $value;
    }

    /**
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        $this->keyValue[$offset];
    }
}
