<?php

namespace Metabor\Statemachine;

use Metabor\Event\Event;
use Metabor\KeyValue\Nullable;
use Metabor\Named;
use Metabor\NamedCollection;
use Metabor\Statemachine\Util\ArrayAccessToArrayConverter;
use MetaborStd\MetadataInterface;
use MetaborStd\Statemachine\StateInterface;
use MetaborStd\Statemachine\TransitionInterface;

/**
 * @author Oliver Tischlinger
 */
class State extends Named implements StateInterface, \ArrayAccess, MetadataInterface
{
    /** @var \SplObjectStorage */
    private \SplObjectStorage $transitions;
    private NamedCollection $events;
    private \ArrayAccess $metadata;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->transitions = new \SplObjectStorage();
        $this->events = new NamedCollection();
        $this->metadata = new Nullable();
    }

    public function getTransitions(): \Traversable
    {
        return clone $this->transitions;
    }

    public function addTransition(TransitionInterface $transition): void
    {
        $this->transitions->attach($transition);
        $eventName = $transition->getEventName();
        if ($eventName) {
            $this->getEvent($eventName);
        }
    }

    public function getEventNames(): array
    {
        return $this->events->getNames();
    }

    public function hasEvent(string $name): bool
    {
        return $this->events->has($name);
    }

    public function getEvent(string $name): Event
    {
        if ($this->events->has($name)) {
            $event = $this->events->get($name);
        } else {
            $event = new Event($name);
            $this->events->add($event);
        }
        return $event;
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->metadata->offsetExists($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->metadata->offsetGet($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->metadata->offsetSet($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->metadata->offsetUnset($offset);
    }

    public function getMetadata(): array
    {
        $converter = new ArrayAccessToArrayConverter($this->metadata);
        return $converter->toArray();
    }
}
