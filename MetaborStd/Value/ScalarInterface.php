<?php
namespace MetaborStd\Value;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
interface ScalarInterface extends MixedInterface
{

    /**
     *
     * @return boolean|double|integer|null|string
     */
    public function getValue();
}
