<?php
namespace Example\Order\Condition;

use Example\Order\Order;
use MetaborInterface\Statemachine\ConditionInterface;
use InvalidArgumentException;
use ArrayAccess;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
class AuthorizedSuccessful implements ConditionInterface {
	
	/**
	 *
	 * @see MetaborInterface\Statemachine.ConditionInterface::checkCondition()
	 */
	public function checkCondition($subject, ArrayAccess $context) {
		return ($context ['authorize result'] == 'successful');
	}
	
	/**
	 *
	 * @see MetaborInterface.NamedInterface::getName()
	 */
	public function getName() {
		return 'authorized successful';
	}

}