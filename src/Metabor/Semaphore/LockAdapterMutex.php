<?php

namespace Metabor\Semaphore;

use MetaborStd\Semaphore\LockAdapterInterface;
use MetaborStd\Semaphore\MutexInterface;

/**
 * @author Oliver Tischlinger
 */
class LockAdapterMutex implements MutexInterface
{
    private LockAdapterInterface $lockAdapter;
    private string $resourceName;
    private bool $acquired = false;

    public function __construct(LockAdapterInterface $lockAdapter, string $resourceName)
    {
        $this->lockAdapter = $lockAdapter;
        $this->resourceName = $resourceName;
    }

    public function acquireLock(): bool
    {
        if ($this->acquired) {
            return true;
        }
        $result = $this->lockAdapter->acquireLock($this->resourceName);
        if ($result) {
            $this->acquired = true;
        }
        return $result;
    }

    public function releaseLock(): bool
    {
        $result = $this->lockAdapter->releaseLock($this->resourceName);
        $this->acquired = false;
        return $result;
    }

    public function isLocked(): bool
    {
        return $this->lockAdapter->isLocked($this->resourceName);
    }

    public function isAcquired(): bool
    {
        return $this->acquired;
    }
}
