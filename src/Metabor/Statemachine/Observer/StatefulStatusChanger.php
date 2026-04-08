<?php

namespace Metabor\Statemachine\Observer;

use MetaborStd\Statemachine\StatefulInterface;
use MetaborStd\Statemachine\StatemachineInterface;

/**
 * @author Oliver Tischlinger
 */
class StatefulStatusChanger implements \SplObserver
{
    public function update(\SplSubject $subject): void
    {
        if ($subject instanceof StatemachineInterface) {
            $statefulSubject = $subject->getSubject();
            if ($statefulSubject instanceof StatefulInterface) {
                $statefulSubject->setCurrentStateName($subject->getCurrentState()->getName());
            }
        }
    }
}
