<?php

namespace Metabor\Event;

use Metabor\KeyValue\Nullable;
use Metabor\Named;
use Metabor\NamedCollection;
use Metabor\Observer\Subject;
use Metabor\Statemachine\Util\ArrayAccessToArrayConverter;
use MetaborStd\Event\EventInterface;
use MetaborStd\MetadataInterface;
use MetaborStd\NamedInterface;

/**
 * @author Oliver Tischlinger
 */
class Event extends Named implements EventInterface, \ArrayAccess, MetadataInterface
{
    private array $invokeArgs = [];
    private \ArrayAccess $metadata;
    /** @var \SplObjectStorage */
    private \SplObjectStorage $observers;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->metadata = new Nullable();
        $this->observers = new \SplObjectStorage();
    }

    public function getInvokeArgs(): array
    {
        return $this->invokeArgs;
    }

    public function __invoke(mixed ...$args): mixed
    {
        $this->invokeArgs = $args;
        $this->notify();
        $this->invokeArgs = [];
        return null;
    }

    public function getName(): string
    {
        return parent::getName();
    }

    public function attach(\SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    public function detach(\SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function getObservers(): \Traversable
    {
        return $this->observers;
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
