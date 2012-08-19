<?php
namespace Metabor\Observer;
use SplSubject;
use SplObserver;
use SplObjectStorage;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
class Subject implements SplSubject {
	
	/**
	 *
	 * @var SplObjectStorage
	 */
	private $observers;
	
	/**
	 */
	public function __construct() {
		$this->observers = new SplObjectStorage ();
	}
	
	/**
	 *
	 * @see SplSubject::attach()
	 */
	public function attach(SplObserver $observer) {
		$this->observers->attach ( $observer );
	}
	
	/**
	 *
	 * @see SplSubject::detach()
	 */
	public function detach(SplObserver $observer) {
		$this->observers->detach ( $observer );
	}
	
	/**
	 *
	 * @see SplSubject::notify()
	 */
	public function notify() {
		/* @var $observer SplObserver */
		foreach ( $this->observers as $observer ) {
			$observer->update ( $this );
		}
	}

}