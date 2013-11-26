<?php
namespace MetaborStd\Statemachine;
use MetaborStd\NamedInterface;
use ArrayAccess;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
interface ConditionInterface extends NamedInterface
{

    /**
     *
     * @param object $subject        	
     * @param ArrayAccess $context        	
     * @return boolean
     */
    public function checkCondition($subject, ArrayAccess $context);
}
