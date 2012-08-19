<?php
namespace MetaborInterface\Event;

use MetaborInterface\CallbackInterface;
use MetaborInterface\NamedInterface;
use SplSubject;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
interface EventInterface extends NamedInterface, SplSubject, CallbackInterface {
	/**
	 *
	 * @return array
	 */
	public function getInvokeArgs();
}