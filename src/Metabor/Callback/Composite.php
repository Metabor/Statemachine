<?php

namespace Metabor\Callback;

use MetaborStd\CallbackInterface;

/**
 * @author Oliver Tischlinger
 */
class Composite implements CallbackInterface, \Countable, \IteratorAggregate
{
    /** @var \SplObjectStorage */
    private \SplObjectStorage $callbacks;

    public function __construct()
    {
        $this->callbacks = new \SplObjectStorage();
    }

    public function __invoke(mixed ...$args): mixed
    {
        foreach ($this->callbacks as $callback) {
            $callback(...$args);
        }
        return null;
    }

    public function attach(CallbackInterface $callback): void
    {
        $this->callbacks->offsetSet($callback, null);
    }

    public function detach(CallbackInterface $callback): void
    {
        $this->callbacks->offsetUnset($callback);
    }

    public function getIterator(): \Traversable
    {
        return $this->callbacks;
    }

    public function count(): int
    {
        return $this->callbacks->count();
    }
}
