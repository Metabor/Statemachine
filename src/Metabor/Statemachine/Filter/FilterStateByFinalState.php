<?php

namespace Metabor\Statemachine\Filter;

/**
 * @author otischlinger
 */
class FilterStateByFinalState extends \FilterIterator
{
    /**
     * @see FilterIterator::accept()
     */
    public function accept(): bool
    {
        /* @var $state \MetaborStd\Statemachine\StateInterface */
        $state = $this->current();

        return (iterator_count($state->getTransitions()) == 0);
    }
}
