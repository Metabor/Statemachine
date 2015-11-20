<?php

namespace Metabor\Semaphore;

use MetaborStd\Semaphore\LockAdapterInterface;
use MetaborStd\Semaphore\MutexInterface;

class LockAdapterMutex implements MutexInterface
{
    /**
     * @var LockAdapterInterface
     */
    private $lockAdapter;

    /**
     * @var string
     */
    private $resourceName;

    /**
     * @var bool
     */
    private $acquired = false;

    /**
     * @param LockAdapterInterface $lockAdapter
     * @param string               $resourceName
     */
    public function __construct(LockAdapterInterface $lockAdapter, $resourceName)
    {
        $this->lockAdapter = $lockAdapter;
        $this->resourceName = $resourceName;
    }

    /**
     * @see \MetaborStd\Semaphore\MutexInterface::releaseLock()
     */
    public function releaseLock()
    {
        if ($this->acquired) {
            $result = $this->lockAdapter->releaseLock($this->resourceName);
            if ($result) {
                $this->acquired = false;
            }

            return $result;
        } else {
            return false;
        }
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
        if (!$this->acquired) {
            $this->acquired = $this->lockAdapter->acquireLock($this->resourceName);
        }

        return $this->acquired;
    }

    /**
     * @see \MetaborStd\Semaphore\MutexInterface::isLocked()
     */
    public function isLocked()
    {
        return $this->lockAdapter->isLocked($this->resourceName);
    }
}
