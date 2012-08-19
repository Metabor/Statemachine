<?php
namespace MetaborInterface\Value;

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
    public function getValue ();
}