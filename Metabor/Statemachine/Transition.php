<?php
namespace Metabor\Statemachine;
use MetaborInterface\Statemachine\ConditionInterface;
use MetaborInterface\Statemachine\TransitionInterface;
use MetaborInterface\Statemachine\StateInterface;
use MetaborInterface\Event\EventInterface;
use ArrayAccess;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
class Transition implements TransitionInterface {
	
	/**
	 *
	 * @var StateInterface
	 */
	private $targetState;
	
	/**
	 *
	 * @var string
	 */
	private $eventName;
	
	/**
	 *
	 * @var ConditionInterface
	 */
	private $condition;
	
	/**
	 *
	 * @param StateInterface $targetState        	
	 * @param string $eventName        	
	 * @param ConditionInterface $condition        	
	 */
	public function __construct(StateInterface $targetState, $eventName = null, ConditionInterface $condition = null) {
		$this->targetState = $targetState;
		$this->eventName = $eventName;
		$this->condition = $condition;
	}
	
	/**
	 *
	 * @see MetaborInterface\Statemachine.TransitionInterface::getTargetState()
	 */
	public function getTargetState() {
		return $this->targetState;
	}
	
	/**
	 *
	 * @see MetaborInterface\Statemachine.TransitionInterface::getEventName()
	 */
	public function getEventName() {
		return $this->eventName;
	}
	
	/**
	 *
	 * @see MetaborInterface\Statemachine.TransitionInterface::getConditionName()
	 */
	public function getConditionName() {
		if ($this->condition) {
			return $this->condition->getName ();
		}
	}
	
	/**
	 *
	 * @see MetaborInterface\Statemachine.TransitionInterface::isActive()
	 */
	public function isActive($subject, ArrayAccess $context, EventInterface $event = null) {
		if ($event) {
			$result = ($event->getName () == $this->eventName);
		} else {
			$result = is_null ( $this->eventName );
		}
		if ($this->condition) {
			$result = $result && $this->condition->checkCondition ( $subject, $context );
		}
		return $result;
	}

}