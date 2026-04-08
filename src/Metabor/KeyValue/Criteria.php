<?php

namespace Metabor\KeyValue;

/**
 * @author Oliver Tischlinger
 */
class Criteria extends \ArrayObject
{
    public function check(\ArrayAccess $keyvalue): bool
    {
        foreach ($this as $key => $value) {
            if (!($keyvalue->offsetExists($key) && ($keyvalue->offsetGet($key) === $value))) {
                return false;
            }
        }

        return true;
    }
}
