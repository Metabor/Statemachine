<?php

namespace Metabor\Observer;

/**
 * @author Oliver Tischlinger
 */
class Subject implements \SplSubject
{
    /**
     * @var \SplObjectStorage
     */
    private $observers;

    /**
     */
    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }

    /**
     * @see \SplSubject::attach()
     */
    public function attach(\SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    /**
     * @see \SplSubject::detach()
     */
    public function detach(\SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    /**
     * @return void
     */
    public function notify(): void
    {
        /* @var $observer \SplObserver */
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    /**
     * @return \Traversable
     */
    public function getObservers()
    {
        return $this->observers;
    }
}
