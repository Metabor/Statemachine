<?php

namespace Metabor\Statemachine\Observer;

use MetaborStd\Statemachine\StatemachineInterface;

/**
 * Triggers automatically an event if the status changes and the new status has it.
 *
 * @author otischlinger
 */
class OnEnterObserver implements \SplObserver
{
    const DEFAULT_EVENT_NAME = 'onEnter';

    /**
     * @var string
     */
    private $eventName;

    /**
     * @param string $eventName
     */
    public function __construct($eventName = self::DEFAULT_EVENT_NAME)
    {
        $this->eventName = $eventName;
    }

    /**
     * @see SplObserver::update()
     */
    public function update(\SplSubject $stateMachine)
    {
        if ($stateMachine instanceof StatemachineInterface) {
            if ($stateMachine->getCurrentState()->hasEvent($this->eventName)) {
                $stateMachine->triggerEvent($this->eventName);
            }
        }
    }
}
