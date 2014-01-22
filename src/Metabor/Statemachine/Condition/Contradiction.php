<?php
namespace Metabor\Statemachine\Condition;

use Metabor\Named;
use MetaborStd\Statemachine\ConditionInterface;
use ArrayAccess;

/**
 * @author otischlinger
 *
 */
class Contradiction extends Named implements ConditionInterface
{
    /**
     *
     * @see MetaborStd\Statemachine.ConditionInterface::checkCondition()
     */
    public function checkCondition($subject, ArrayAccess $context)
    {
        return false;
    }

}
