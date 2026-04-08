<?php

namespace Metabor;

use MetaborStd\StringConverterInterface;

/**
 * @author Oliver Tischlinger
 */
class StringConverter implements StringConverterInterface
{
    public function convertToString(mixed $source): string
    {
        return (string) $source;
    }
}
