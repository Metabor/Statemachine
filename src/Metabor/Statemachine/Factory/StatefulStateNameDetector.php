<?php

namespace Metabor\Statemachine\Factory;

use MetaborStd\Statemachine\Factory\StateNameDetectorInterface;
use MetaborStd\Statemachine\StatefulInterface;

/**
 * @author Oliver Tischlinger
 */
class StatefulStateNameDetector implements StateNameDetectorInterface
{
    public function detectCurrentStateName(object $subject): ?string
    {
        if (!$subject instanceof StatefulInterface) {
            throw new \InvalidArgumentException(
                sprintf('Subject of type "%s" does not implement StatefulInterface', get_class($subject))
            );
        }
        return $subject->getCurrentStateName();
    }
}
