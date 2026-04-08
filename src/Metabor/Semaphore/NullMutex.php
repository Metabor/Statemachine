<?php

namespace Metabor\Semaphore;

use MetaborStd\Semaphore\MutexInterface;

/**
 * @author Oliver Tischlinger
 */
class NullMutex implements MutexInterface
{
    private bool $acquired = false;

    public function acquireLock(): bool
    {
        $this->acquired = true;
        return true;
    }

    public function releaseLock(): bool
    {
        $this->acquired = false;
        return true;
    }

    public function isLocked(): bool
    {
        return $this->acquired;
    }

    public function isAcquired(): bool
    {
        return $this->acquired;
    }
}
