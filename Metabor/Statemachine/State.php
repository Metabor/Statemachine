<?php
namespace Metabor\Statemachine;
use Metabor\Event\Event;

use MetaborInterface\Statemachine\TransitionInterface;
use MetaborInterface\Statemachine\StateInterface;
use Metabor\NamedCollection;
use Metabor\Named;
use MetaborInterface\Statemachine\ProcessInterface;
use SplObjectStorage;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
class State extends Named implements StateInterface {
	
	/**
	 *
	 * @var \SplObjectStorage
	 */
	private $transitions;
	
	/**
	 *
	 * @var NamedCollection
	 */
	private $events;
	
	/**
	 *
	 * @param string $name        	
	 */
	public function __construct($name) {
		parent::__construct ( $name );
		$this->transitions = new SplObjectStorage ();
		$this->events = new NamedCollection ();
	}
	
	/**
	 *
	 * @return multitype:TransitionInterface
	 */
	public function getTransitions() {
		return $this->transitions;
	}
	
	/**
	 *
	 * @param TransitionInterface $transition        	
	 */
	public function addTransition(TransitionInterface $transition) {
		$this->transitions->attach ( $transition );
		$eventName = $transition->getEventName ();
		if ($eventName) {
			$this->getEvent ( $eventName );
		}
	}
	
	/**
	 *
	 * @see MetaborInterface\Statemachine.StateInterface::getEventNames()
	 */
	public function getEventNames() {
		return $this->events->getNames ();
	}
	
	/**
	 *
	 * @see MetaborInterface\Statemachine.StateInterface::hasEvent()
	 */
	public function hasEvent($name) {
		return $this->events->has ( $name );
	}
	
	/**
	 *
	 * @see MetaborInterface\Statemachine.StateInterface::getEvent()
	 */
	public function getEvent($name) {
		if ($this->events->has ( $name )) {
			$event = $this->events->get ( $name );
		} else {
			$event = new Event ( $name );
			$this->events->add ( $event );
		}
		return $event;
	}

}