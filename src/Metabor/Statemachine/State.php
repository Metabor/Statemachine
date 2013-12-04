<?php
namespace Metabor\Statemachine;
use Metabor\KeyValue\Nullable;

use Metabor\Event\Event;

use MetaborStd\Statemachine\TransitionInterface;
use MetaborStd\Statemachine\StateInterface;
use Metabor\NamedCollection;
use Metabor\Named;
use MetaborStd\Statemachine\ProcessInterface;
use SplObjectStorage;
use ArrayAccess;

/**
 *
 * @author Oliver Tischlinger
 *        
 */
class State extends Named implements StateInterface, ArrayAccess
{

    /**
     *
     * @var \SplObjectStorage
     */
    private $transitions;

    /**
     *
     * @var NamedCollection
     */
    private $events;

    /**
     * 
     * @var \ArrayAccess
     */
    private $metadata;

    /**
     *
     * @param string $name        	
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->transitions = new SplObjectStorage();
        $this->events = new NamedCollection();
        $this->metadata = new Nullable();
    }

    /**
     *
     * @return multitype:TransitionInterface
     */
    public function getTransitions()
    {
        return $this->transitions;
    }

    /**
     *
     * @param TransitionInterface $transition        	
     */
    public function addTransition(TransitionInterface $transition)
    {
        $this->transitions->attach($transition);
        $eventName = $transition->getEventName();
        if ($eventName) {
            $this->getEvent($eventName);
        }
    }

    /**
     *
     * @see MetaborStd\Statemachine.StateInterface::getEventNames()
     */
    public function getEventNames()
    {
        return $this->events->getNames();
    }

    /**
     *
     * @see MetaborStd\Statemachine.StateInterface::hasEvent()
     */
    public function hasEvent($name)
    {
        return $this->events->has($name);
    }

    /**
     *
     * @see MetaborStd\Statemachine.StateInterface::getEvent()
     */
    public function getEvent($name)
    {
        if ($this->events->has($name)) {
            $event = $this->events->get($name);
        } else {
            $event = new Event($name);
            $this->events->add($event);
        }
        return $event;
    }

    /**
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return $this->metadata->offsetExists($offset);
    }

    /**
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return $this->metadata->offsetGet($offset);
    }

    /**
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        $this->metadata->offsetSet($offset, $value);
    }

    /**
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        $this->metadata->offsetUnset($offset);
    }

}
