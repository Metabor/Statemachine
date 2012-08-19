<?php
namespace MetaborInterface\Value;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
interface IntegerInterface extends NumericInterface
{

    /**
     *
     * @return integer
     */
    public function getValue ();
}