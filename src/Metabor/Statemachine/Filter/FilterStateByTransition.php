<?php

namespace Metabor\Statemachine\Filter;

/**
 * @author otischlinger
 */
class FilterStateByTransition extends \FilterIterator
{
    /**
     * @see FilterIterator::accept()
     */
    public function accept(): bool
    {
        /* @var $state \MetaborStd\Statemachine\StateInterface */
        $state = $this->current();

        /* @var $transition \MetaborStd\Statemachine\TransitionInterface */
        foreach ($state->getTransitions() as $transition) {
            if (!$transition->getEventName()) {
                return true;
            }
        }

        return false;
    }
}
