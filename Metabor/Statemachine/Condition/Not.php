<?php
namespace Metabor\Statemachine\Condition;

use MetaborInterface\Statemachine\ConditionInterface;
use ArrayAccess;

class Not implements ConditionInterface {
	
	/**
	 *
	 * @var ConditionInterface
	 */
	private $condition;
	/**
	 *
	 * @param ConditionInterface $condition        	
	 */
	public function __construct(ConditionInterface $condition) {
		$this->condition = $condition;
	}
	
	/**
	 *
	 * @see MetaborInterface\Statemachine.ConditionInterface::checkCondition()
	 */
	public function checkCondition($subject, ArrayAccess $context) {
		return ! $this->condition->checkCondition ( $subject, $context );
	}
	
	/**
	 *
	 * @see MetaborInterface.NamedInterface::getName()
	 */
	public function getName() {
		return 'not ( ' . $this->condition->getName () . ' )';
	}

}