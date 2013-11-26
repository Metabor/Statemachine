<?php
namespace MetaborStd\Value;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
interface NumericInterface extends ScalarInterface
{

    /**
     *
     * @return integer|float
     */
    public function getValue();
}
