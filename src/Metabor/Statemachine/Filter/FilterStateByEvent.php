<?php
namespace Metabor\Statemachine\Filter;
use MetaborStd\Statemachine\StateInterface;

/**
 * @author otischlinger
 *
 */
class FilterStateByEvent extends \FilterIterator
{
    /**
     * @var string
     */
    private $eventName;

    /**
     * 
     * @param \Traversable $iterator
     * @param string $eventName
     */
    public function __construct(\Traversable $iterator, $eventName)
    {
        parent::__construct(new \IteratorIterator($iterator));
        $this->eventName = $eventName;
    }

    /**
     * @see FilterIterator::accept()
     */
    public function accept()
    {
        /* @var $state StateInterface */
        $state = $this->current();
        return $state->hasEvent($this->eventName);
    }
}
