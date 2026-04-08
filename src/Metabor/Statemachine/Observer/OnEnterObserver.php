<?php

namespace Metabor\Statemachine\Observer;

use MetaborStd\Statemachine\StatemachineInterface;

/**
 * @author Oliver Tischlinger
 */
class OnEnterObserver implements \SplObserver
{
    public const DEFAULT_EVENT_NAME = 'onEnter';

    private string $eventName;

    public function __construct(string $eventName = self::DEFAULT_EVENT_NAME)
    {
        $this->eventName = $eventName;
    }

    public function update(\SplSubject $subject): void
    {
        if ($subject instanceof StatemachineInterface) {
            $currentState = $subject->getCurrentState();
            if ($currentState->hasEvent($this->eventName)) {
                $context = $subject->getCurrentContext();
                $subject->triggerEvent($this->eventName, $context);
            }
        }
    }
}
