<?php

namespace Metabor\Callback;

use MetaborStd\CallbackInterface;

/**
 * @author Oliver Tischlinger
 */
class Composite implements CallbackInterface, \Countable, \IteratorAggregate
{
    /**
     * @var \SplObjectStorage
     */
    private $callbacks;

    /**
     *
     */
    public function __construct()
    {
        $this->callbacks = new \SplObjectStorage();
    }

    /**
     * @see MetaborStd.CallbackInterface::__invoke()
     */
    public function __invoke()
    {
        $args = func_get_args();
        /* @var $callback callable */
        foreach ($this->callbacks as $callback) {
            if (empty($args)) {
                $callback();
            } else {
                call_user_func_array($callback, $args);
            }
        }
    }

    /**
     * @param CallbackInterface $callback
     */
    public function attach(CallbackInterface $callback): void
    {
        $this->callbacks->attach($callback);
    }

    /**
     * @param CallbackInterface $callback
     */
    public function detach(CallbackInterface $callback): void
    {
        $this->callbacks->detach($callback);
    }

    /**
     * @return \Iterator
     */
    public function getIterator(): \Traversable
    {
        return $this->callbacks;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->callbacks->count();
    }
}
