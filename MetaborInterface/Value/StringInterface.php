<?php
namespace MetaborInterface\Value;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
interface StringInterface extends ScalarInterface
{

    /**
     *
     * @return string
     */
    public function getValue ();
}