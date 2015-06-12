<?php
namespace Metabor;

use MetaborStd\NamedInterface;
use MetaborStd\StringConverterInterface;

/**
 * @author Oliver Tischlinger
 */
class StringConverter implements StringConverterInterface
{
    /**
     * @param mixed $source
     *
     * @return string
     */
    public function convertToString($source)
    {
        if (is_object($source)) {
            if ($source instanceof NamedInterface) {
                return $source->getName();
            } elseif (method_exists($source, '__toString')) {
                return (string) $source;
            } else {
                return get_class($source);
            }
        } elseif (is_scalar($source) || is_null($source)) {
            return (string) $source;
        } else {
            return gettype($source);
        }
    }
}
