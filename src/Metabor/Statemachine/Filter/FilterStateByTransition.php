<?php

namespace Metabor\Statemachine\Filter;

use MetaborStd\Statemachine\StateInterface;

/**
 * @author Oliver Tischlinger
 */
class FilterStateByTransition extends \FilterIterator
{
    public function __construct(\Traversable $iterator)
    {
        parent::__construct(new \IteratorIterator($iterator));
    }

    public function accept(): bool
    {
        $state = $this->current();
        if (!$state instanceof StateInterface) {
            return false;
        }
        foreach ($state->getTransitions() as $transition) {
            if ($transition->getEventName() === null) {
                return true;
            }
        }
        return false;
    }
}
