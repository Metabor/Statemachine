<?php

namespace Metabor\Statemachine\Filter;

use MetaborStd\Statemachine\StateInterface;

/**
 * @author Oliver Tischlinger
 */
class FilterStateByEvent extends \FilterIterator
{
    private string $eventName;

    public function __construct(\Traversable $iterator, string $eventName)
    {
        parent::__construct(new \IteratorIterator($iterator));
        $this->eventName = $eventName;
    }

    public function accept(): bool
    {
        $state = $this->current();
        return $state instanceof StateInterface && $state->hasEvent($this->eventName);
    }
}
