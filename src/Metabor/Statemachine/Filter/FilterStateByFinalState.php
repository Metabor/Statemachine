<?php

namespace Metabor\Statemachine\Filter;

use MetaborStd\Statemachine\StateInterface;

/**
 * @author Oliver Tischlinger
 */
class FilterStateByFinalState extends \FilterIterator
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
        $transitions = iterator_to_array($state->getTransitions());
        return empty($transitions);
    }
}
