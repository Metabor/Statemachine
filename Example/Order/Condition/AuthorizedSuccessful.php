<?php
namespace Example\Order\Condition;

use MetaborStd\Statemachine\ConditionInterface;
use ArrayAccess;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
class AuthorizedSuccessful implements ConditionInterface
{

    /**
     *
     * @see MetaborStd\Statemachine.ConditionInterface::checkCondition()
     */
    public function checkCondition($subject, ArrayAccess $context)
    {
        return ($context['authorize result'] == 'successful');
    }

    /**
     *
     * @see MetaborStd.NamedInterface::getName()
     */
    public function getName()
    {
        return 'authorized successful';
    }
}
