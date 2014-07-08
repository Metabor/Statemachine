<?php
namespace Metabor\Statemachine\Observer;

use MetaborStd\Statemachine\StatefulInterface;
use MetaborStd\Statemachine\StatemachineInterface;

/**
 * @author otischlinger
 *
 */
class StatefulStatusChanger implements \SplObserver
{
    /**
     * @see SplObserver::update()
     */
    public function update(\SplSubject $stateMachine)
    {
        if ($stateMachine instanceof StatemachineInterface) {
            $subject = $stateMachine->getSubject();
            if ($subject instanceof StatefulInterface) {
                $stateName = $stateMachine->getCurrentState()->getName();
                $subject->setCurrentStateName($stateName);
            }
        }
    }
}
