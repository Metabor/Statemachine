<?php

namespace Metabor\Adapter;

use MetaborStd\Semaphore\LockAdapterInterface;
use NinjaMutex\Lock\LockInterface;

/**
 * @author Oliver Tischlinger
 */
class NinjaMutexLockAdapter implements LockAdapterInterface
{
    /**
     * @var LockInterface
     */
    private $ninjaMutexLock;

    /**
     * @var null|int
     */
    private $timeout;

    /**
     * @param LockInterface $ninjaMutexLock
     * @param  null|int $timeout 1. null if you want blocking lock
     *                           2. 0 if you want just lock and go
     *                           3. $timeout > 0 if you want to wait for lock some time (in milliseconds)
     */
    public function __construct(LockInterface $ninjaMutexLock, $timeout = null)
    {
        $this->ninjaMutexLock = $ninjaMutexLock;
        $this->timeout = $timeout;
    }

    /**
     * @param string $resourceName
     * @return bool
     */
    public function acquireLock($resourceName)
    {
        return $this->ninjaMutexLock->acquireLock($resourceName, $this->timeout);
    }

    /**
     * @param string $resourceName
     * @return bool
     */
    public function releaseLock($resourceName)
    {
        return $this->ninjaMutexLock->releaseLock($resourceName);
    }

    /**
     * @param string $resourceName
     * @return bool
     */
    public function isLocked($resourceName)
    {
        return $this->ninjaMutexLock->isLocked($resourceName);
    }
}
