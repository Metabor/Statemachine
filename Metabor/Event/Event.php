<?php
namespace Metabor\Event;

use Metabor\Observer\Subject;
use MetaborInterface\Event\EventInterface;
use SplObserver;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
class Event extends Subject implements EventInterface {
	
	/**
	 *
	 * @var string
	 */
	private $name;
	
	/**
	 *
	 * @var array
	 */
	private $invokeArgs = array ();
	
	/**
	 *
	 * @param string $name        	
	 */
	public function __construct($name) {
		parent::__construct ();
		$this->name = $name;
	}
	
	/**
	 *
	 * @see MetaborInterface\Event.EventInterface::getInvokeArgs()
	 */
	public function getInvokeArgs() {
		return $this->invokeArgs;
	}
	
	/**
	 */
	public function __invoke() {
		$this->invokeArgs = func_get_args ();
		$this->notify ();
		$this->invokeArgs = array ();
	}
	
	/**
	 *
	 * @see Metabor.Named::getName()
	 */
	public function getName() {
		return $this->name;
	}

}