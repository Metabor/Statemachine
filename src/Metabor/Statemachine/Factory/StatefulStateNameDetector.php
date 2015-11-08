<?php

namespace Metabor\Statemachine\Factory;

use MetaborStd\Statemachine\Factory\StateNameDetectorInterface;
use MetaborStd\Statemachine\StatefulInterface;

/**
 * @author otischlinger
 */
class StatefulStateNameDetector implements StateNameDetectorInterface
{
    /**
     * @see \MetaborStd\Statemachine\Factory\StateNameDetectorInterface::detectCurrentStateName()
     */
    public function detectCurrentStateName($subject)
    {
        if ($subject instanceof StatefulInterface) {
            return $subject->getCurrentStateName();
        } else {
            throw new \InvalidArgumentException('Subject has to implement the StatefulInterface!');
        }
    }
}
