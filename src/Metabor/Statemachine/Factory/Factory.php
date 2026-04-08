<?php

namespace Metabor\Statemachine\Factory;

use Metabor\Statemachine\Statemachine;
use MetaborStd\Semaphore\MutexFactoryInterface;
use MetaborStd\Statemachine\Factory\FactoryInterface;
use MetaborStd\Statemachine\Factory\ProcessDetectorInterface;
use MetaborStd\Statemachine\Factory\StateNameDetectorInterface;
use MetaborStd\Statemachine\Factory\TransitionSelectorInterface;
use MetaborStd\Statemachine\StatemachineInterface;

/**
 * @author Oliver Tischlinger
 */
class Factory implements FactoryInterface
{
    private ProcessDetectorInterface $processDetector;
    private ?StateNameDetectorInterface $stateNameDetector;
    /** @var \SplObjectStorage */
    private \SplObjectStorage $statemachineObserver;
    private ?TransitionSelectorInterface $transitonSelector = null;
    private ?MutexFactoryInterface $mutexFactory = null;

    public function __construct(ProcessDetectorInterface $processDetector, ?StateNameDetectorInterface $stateNameDetector = null)
    {
        $this->processDetector = $processDetector;
        $this->stateNameDetector = $stateNameDetector;
        $this->statemachineObserver = new \SplObjectStorage();
    }

    public function setMutexFactory(?MutexFactoryInterface $mutexFactory = null): void
    {
        $this->mutexFactory = $mutexFactory;
    }

    public function setTransitonSelector(TransitionSelectorInterface $transitionSelector): void
    {
        $this->transitonSelector = $transitionSelector;
    }

    public function attachStatemachineObserver(\SplObserver $observer): void
    {
        $this->statemachineObserver->attach($observer);
    }

    public function detachStatemachineObserver(\SplObserver $observer): void
    {
        $this->statemachineObserver->detach($observer);
    }

    public function getStatemachineObserver(): \Traversable
    {
        return $this->statemachineObserver;
    }

    public function createStatemachine(object $subject): StatemachineInterface
    {
        $process = $this->processDetector->detectProcess($subject);
        $stateName = $this->stateNameDetector?->detectCurrentStateName($subject);
        $mutex = $this->mutexFactory?->createMutex($subject);

        $statemachine = new Statemachine($subject, $process, $stateName, $this->transitonSelector, $mutex);

        foreach ($this->statemachineObserver as $observer) {
            $statemachine->attach($observer);
        }

        return $statemachine;
    }
}
