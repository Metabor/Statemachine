<?php
namespace MetaborStd\Statemachine;
use ArrayAccess;
use MetaborStd\Event\EventInterface;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
interface TransitionInterface
{

    /**
     *
     * @return \MetaborStd\Statemachine\StateInterface
     */
    public function getTargetState();

    /**
     *
     * @param object $subject        	
     * @param ArrayAccess $context        	
     * @param EventInterface $event        	
     * @return boolean
     */
    public function isActive($subject, ArrayAccess $context, EventInterface $event = null);

    /**
     *
     * @return <string,null>
     */
    public function getEventName();

    /**
     *
     * @return <string,null>
     */
    public function getConditionName();

}
