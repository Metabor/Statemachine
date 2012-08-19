<?php
namespace MetaborInterface\Value;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
interface BooleanInterface extends ScalarInterface
{

    /**
     *
     * @return boolean
     */
    public function getValue ();
}