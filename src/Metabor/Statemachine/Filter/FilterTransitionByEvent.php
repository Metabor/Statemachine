<?php

namespace Metabor\Statemachine\Filter;

use MetaborStd\Statemachine\TransitionInterface;

/**
 * @author Oliver Tischlinger
 */
class FilterTransitionByEvent extends \FilterIterator
{
    private string $eventName;

    public function __construct(\Iterator $iterator, string $eventName)
    {
        parent::__construct($iterator);
        $this->eventName = $eventName;
    }

    public function accept(): bool
    {
        $transition = $this->current();
        return $transition instanceof TransitionInterface && $transition->getEventName() === $this->eventName;
    }
}
