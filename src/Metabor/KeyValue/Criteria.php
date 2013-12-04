<?php
namespace Metabor\KeyValue;
use ArrayObject;
use ArrayAccess;

/**
 * @author otischlinger
 *
 */
class Criteria extends ArrayObject
{
    /**
     * @param ArrayAccess $keyvalue
     * @return boolean
     */
    public function check(ArrayAccess $keyvalue)
    {
        foreach ($this as $key => $value) {
            if (!($keyvalue->offsetExists($key) && ($keyvalue->offsetGet($key) === $key))) {
                return false;
            }
        }
        return true;
    }
}
