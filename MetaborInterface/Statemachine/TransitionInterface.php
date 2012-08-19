<?php
namespace MetaborInterface\Statemachine;
use ArrayAccess;
use MetaborInterface\Event\EventInterface;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
interface TransitionInterface {
	
	/**
	 *
	 * @return \MetaborInterface\Statemachine\StateInterface
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