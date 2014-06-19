<?php
namespace Metabor\Statemachine\Filter;

/**
 * @author otischlinger
 *
 */
class FilterStateByTransition extends \FilterIterator
{
    /**
     * @see FilterIterator::accept()
     */
    public function accept()
    {
        /* @var $state StateInterface */
        $state = $this->current();

        /* @var $transition TransitionInterface */
        foreach ($state->getTransitions() as $transition) {
            if (!$transition->getEventName()) {
                return true;
            }
        }
        return false;
    }
}
