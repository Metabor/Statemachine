<?php
namespace Metabor\Statemachine\Condition;

use Metabor\Named;
use MetaborStd\Statemachine\ConditionInterface;
use ArrayAccess;

/**
 * @author otischlinger
 *
 */
class Tautology extends Named implements ConditionInterface
{
    /**
     *
     * @see MetaborStd\Statemachine.ConditionInterface::checkCondition()
     */
    public function checkCondition($subject, ArrayAccess $subject)
    {
        return true;
    }

}
