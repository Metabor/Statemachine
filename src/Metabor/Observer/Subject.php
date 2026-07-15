<?php

namespace Metabor\Observer;

/**
 * @author Oliver Tischlinger
 */
class Subject implements \SplSubject
{
    /** @var \SplObjectStorage */
    private \SplObjectStorage $observers;

    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }

    public function attach(\SplObserver $observer): void
    {
        $this->observers->offsetSet($observer, null);
    }

    public function detach(\SplObserver $observer): void
    {
        $this->observers->offsetUnset($observer);
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
}
