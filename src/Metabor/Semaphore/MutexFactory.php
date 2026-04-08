<?php

namespace Metabor\Semaphore;

use Metabor\StringConverter;
use MetaborStd\Semaphore\LockAdapterInterface;
use MetaborStd\Semaphore\MutexFactoryInterface;
use MetaborStd\Semaphore\MutexInterface;
use MetaborStd\StringConverterInterface;

/**
 * @author Oliver Tischlinger
 */
class MutexFactory implements MutexFactoryInterface
{
    private LockAdapterInterface $lockAdapter;
    private StringConverterInterface $stringConverter;

    public function __construct(LockAdapterInterface $lockAdapter, ?StringConverterInterface $stringConverter = null)
    {
        $this->lockAdapter = $lockAdapter;
        $this->stringConverter = $stringConverter ?? new StringConverter();
    }

    public function createMutex(mixed $subject): MutexInterface
    {
        $resourceName = $this->stringConverter->convertToString($subject);
        return new LockAdapterMutex($this->lockAdapter, $resourceName);
    }
}
