<?php

namespace Metabor\Adapter;

use MetaborStd\Semaphore\LockAdapterInterface;
use NinjaMutex\Lock\LockInterface;

/**
 * @author Oliver Tischlinger
 */
class NinjaMutexLockAdapter implements LockAdapterInterface
{
    private LockInterface $ninjaMutexLock;
    private ?int $timeout;

    /**
     * @param LockInterface $ninjaMutexLock
     * @param null|int $timeout 1. null if you want blocking lock
     *                          2. 0 if you want just lock and go
     *                          3. $timeout > 0 if you want to wait for lock some time (in milliseconds)
     */
    public function __construct(LockInterface $ninjaMutexLock, ?int $timeout = null)
    {
        $this->ninjaMutexLock = $ninjaMutexLock;
        $this->timeout = $timeout;
    }

    public function acquireLock(string $resourceName): bool
    {
        return $this->ninjaMutexLock->acquireLock($resourceName, $this->timeout);
    }

    public function releaseLock(string $resourceName): bool
    {
        return $this->ninjaMutexLock->releaseLock($resourceName);
    }

    public function isLocked(string $resourceName): bool
    {
        return $this->ninjaMutexLock->isLocked($resourceName);
    }
}
