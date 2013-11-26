<?php
namespace Metabor\Statemachine;
use Metabor\Observer\Subject;
use MetaborStd\Event\EventInterface;
use MetaborStd\Statemachine\StatemachineInterface;
use MetaborStd\Statemachine\ProcessInterface;
use MetaborStd\Statemachine\StateInterface;
use MetaborStd\Statemachine\TransitionInterface;
use ArrayAccess;
use ArrayIterator;
use RuntimeException;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
class Statemachine extends Subject implements StatemachineInterface {
	
	/**
	 *
	 * @var ProcessInterface
	 */
	private $process;
	
	/**
	 *
	 * @var object
	 */
	private $subject;
	
	/**
	 *
	 * @var StateInterface
	 */
	private $currentState;
	
	/**
	 *
	 * @param object $subject        	
	 * @param ProcessInterface $process        	
	 * @param string $stateName        	
	 */
	public function __construct($subject, ProcessInterface $process, $stateName = null) {
		parent::__construct ();
		$this->subject = $subject;
		$this->process = $process;
		if ($stateName) {
			$this->currentState = $process->getState ( $stateName );
		} else {
			$this->currentState = $process->getInitialState ();
		}
		$this->checkTransitions ();
	}
	
	/**
	 *
	 * @see MetaborStd\Statemachine.StatemachineInterface::getCurrentState()
	 */
	public function getCurrentState() {
		return $this->currentState;
	}
	
	/**
	 *
	 * @param array $activeTransitions        	
	 * @throws RuntimeException
	 */
	protected function selectTransition(array $activeTransitions) {
		switch (count ( $activeTransitions )) {
			case 0 :
				break;
			case 1 :
				/* @var $transition TransitionInterface */
				$transition = reset ( $activeTransitions );
				$this->currentState = $transition->getTargetState ();
				$this->notify ();
				$this->checkTransitions ();
				break;
			default :
				throw new RuntimeException ( 'More than one transition is active!' );
				break;
		}
	}
	
	/**
	 *
	 * @param ArrayAccess $context        	
	 * @param EventInterface $event        	
	 */
	protected function doCheckTransitions(ArrayAccess $context, EventInterface $event = null) {
		$activeTransitions = array ();
		$transitions = $this->currentState->getTransitions ();
		/* @var $transition TransitionInterface */
		foreach ( $transitions as $transition ) {
			$isActive = $transition->isActive ( $this->subject, $context, $event );
			if ($isActive) {
				$activeTransitions [] = $transition;
			}
		}
		$this->selectTransition ( $activeTransitions );
	}
	
	/**
	 *
	 * @see MetaborStd\Statemachine.StatemachineInterface::triggerEvent()
	 */
	public function triggerEvent($name, ArrayAccess $context = null) {
		if (! $context) {
			$context = new ArrayIterator ( array () );
		}
		if ($this->currentState->hasEvent ( $name )) {
			$event = $this->currentState->getEvent ( $name );
			$event ( $this->subject, $context );
			$this->doCheckTransitions ( $context, $event );
		} else {
			throw new RuntimeException ( 'Current State did not have event "' . $name . '"' );
		}
	}
	
	/**
	 *
	 * @see MetaborStd\Statemachine.StatemachineInterface::checkTransitions()
	 */
	public function checkTransitions() {
		$context = new ArrayIterator ( array () );
		$this->doCheckTransitions ( $context );
	}
	
	/**
	 * 
	 * @see \MetaborStd\Statemachine\StatemachineInterface::getSubject()
	 */
	public function getSubject() {
	    return $this->subject;
	}

}