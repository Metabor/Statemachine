<?php

namespace Metabor\Semaphore;

use MetaborStd\Semaphore\MutexInterface;

class NullMutex implements MutexInterface
{
    /**
     * @var bool
     */
    private $acquired = false;

    /**
     * @see \MetaborStd\Semaphore\MutexInterface::releaseLock()
     */
    public function releaseLock()
    {
        $this->acquired = false;

        return true;
    }

    /**
     * @see \MetaborStd\Semaphore\MutexInterface::isAcquired()
     */
    public function isAcquired()
    {
        return $this->acquired;
    }

    /**
     * @see \MetaborStd\Semaphore\MutexInterface::acquireLock()
     */
    public function acquireLock()
    {
        $this->acquired = true;

        return true;
    }

    /**
     * @see \MetaborStd\Semaphore\MutexInterface::isLocked()
     */
    public function isLocked()
    {
        return false;
    }
}
