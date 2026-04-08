<?php

namespace Metabor\Observer;

/**
 * @author Oliver Tischlinger
 */
class CounterObserver implements \SplObserver
{
    protected int $count = 0;

    public function update(\SplSubject $subject): void
    {
        $this->count++;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function resetCount(): void
    {
        $this->count = 0;
    }
}
