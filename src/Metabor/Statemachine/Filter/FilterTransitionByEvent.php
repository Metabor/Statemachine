<?php

namespace Metabor\Statemachine\Filter;

use MetaborStd\Statemachine\StateInterface;
use MetaborStd\Statemachine\TransitionInterface;

/**
 * @author oliver.tischlinger@metabor.de
 */
class FilterTransitionByEvent extends \FilterIterator
{
    /**
     * @var string
     */
    private $eventName;

    /**
     * @param \Traversable $transitions
     * @param string $eventName
     */
    public function __construct(\Traversable $transitions, $eventName)
    {
        parent::__construct(new \IteratorIterator($states));
        $this->eventName = $eventName;
    }

    /**
     * @see FilterIterator::accept()
     */
    public function accept()
    {
        /* @var $transition TransitionInterface */
        $transition = $this->current();

        return ($transition->getEventName() === $this->eventName);
    }
}
