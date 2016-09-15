<?php

namespace Metabor\Semaphore;

use MetaborStd\Semaphore\LockAdapterInterface;
use MetaborStd\Semaphore\MutexFactoryInterface;
use MetaborStd\Semaphore\MutexInterface;
use MetaborStd\StringConverterInterface;

class MutexFactory implements MutexFactoryInterface
{
    /**
     * @var LockAdapterInterface
     */
    private $lockAdapter;

    /**
     * @var StringConverterInterface
     */
    private $stringConverter;

    /**
     * @param LockAdapterInterface $lockAdapter
     * @param StringConverterInterface $stringConverter
     */
    public function __construct(LockAdapterInterface $lockAdapter, StringConverterInterface $stringConverter)
    {
        $this->lockAdapter = $lockAdapter;
        $this->stringConverter = $stringConverter;
    }

    /**
     * @param $subject
     * @return MutexInterface
     */
    public function createMutex($subject)
    {
        return new LockAdapterMutex($this->lockAdapter, $this->stringConverter->convertToString($subject));
    }
}
