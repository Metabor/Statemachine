<?php

namespace Metabor\Statemachine\Condition;

use MetaborStd\Statemachine\ConditionInterface;

/**
 * @author Oliver Tischlinger
 */
class Tautology implements ConditionInterface
{
    public function getName(): string
    {
        return 'tautology';
    }

    public function checkCondition(object $subject, \ArrayAccess $context): bool
    {
        return true;
    }
}
