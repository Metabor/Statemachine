<?php

namespace Metabor\Statemachine\Condition;

use MetaborStd\Statemachine\ConditionInterface;

/**
 * @author Oliver Tischlinger
 */
class Not implements ConditionInterface
{
    private ConditionInterface $condition;

    public function __construct(ConditionInterface $condition)
    {
        $this->condition = $condition;
    }

    public function checkCondition(object $subject, \ArrayAccess $context): bool
    {
        return !$this->condition->checkCondition($subject, $context);
    }

    public function getName(): string
    {
        return 'not(' . $this->condition->getName() . ')';
    }
}
