<?php
namespace Example\Order\Condition;

use Example\Order\Order;
use MetaborStd\Statemachine\ConditionInterface;
use InvalidArgumentException;
use ArrayAccess;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
class ShippingDateGreater14Days implements ConditionInterface
{

    /**
     *
     * @see MetaborStd\Statemachine.ConditionInterface::checkCondition()
     */
    public function checkCondition($subject, ArrayAccess $context)
    {
        if (!$subject instanceof Order) {
            throw new InvalidArgumentException('Subject has to be an Order!');
        }
        return ($subject->getNumber() == 'POSTPAYMENT 2');
    }

    /**
     *
     * @see MetaborStd.NamedInterface::getName()
     */
    public function getName()
    {
        return 'shipping-date >= 14 days';
    }

}
