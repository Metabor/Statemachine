<?php

namespace Metabor\Statemachine\Condition;

use MetaborStd\Statemachine\ConditionInterface;

/**
 * @author Oliver Tischlinger
 */
class Contradiction implements ConditionInterface
{
    public function getName(): string
    {
        return 'contradiction';
    }

    public function checkCondition(object $subject, \ArrayAccess $context): bool
    {
        return false;
    }
}
