<?php
namespace Metabor\Statemachine\Condition;

use Metabor\Named;
use MetaborStd\Statemachine\ConditionInterface;

/**
 * @author otischlinger
 */
class Contradiction extends Named implements ConditionInterface
{
    /**
     * @see MetaborStd\Statemachine.ConditionInterface::checkCondition()
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function checkCondition($subject, \ArrayAccess $context)
    {
        return false;
    }
}
