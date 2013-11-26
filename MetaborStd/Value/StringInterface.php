<?php
namespace MetaborStd\Value;

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
    public function getValue();
}
