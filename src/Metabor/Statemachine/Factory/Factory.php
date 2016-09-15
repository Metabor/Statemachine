<?php

namespace Metabor\Statemachine\Factory;

use Metabor\Statemachine\Statemachine;
use MetaborStd\Semaphore\MutexFactoryInterface;
use MetaborStd\Statemachine\Factory\FactoryInterface;
use MetaborStd\Statemachine\Factory\ProcessDetectorInterface;
use MetaborStd\Statemachine\Factory\StateNameDetectorInterface;
use MetaborStd\Statemachine\Factory\TransitionSelectorInterface;

/**
 * @author Oliver Tischlinger
 */
class Factory implements FactoryInterface
{
    /**
     * @var ProcessDetectorInterface
     */
    private $processDetector;

    /**
     * @var StateNameDetectorInterface
     */
    private $stateNameDetector;

    /**
     * @var \SplObjectStorage|\SplObserver[]
     */
    private $statemachineObserver;

    /**
     * @var TransitionSelectorInterface
     */
    private $transitonSelector;

    /**
     * @var MutexFactoryInterface
     */
    private $mutexFactory;

    /**
     * @param ProcessDetectorInterface   $processDetector
     * @param StateNameDetectorInterface $stateNameDetector
     */
    public function __construct(ProcessDetectorInterface $processDetector, StateNameDetectorInterface $stateNameDetector = null)
    {
        $this->processDetector = $processDetector;
        $this->stateNameDetector = $stateNameDetector;
        $this->statemachineObserver = new \SplObjectStorage();
    }

    /**
     * @param MutexFactoryInterface $mutexFactory
     */
    public function setMutexFactory(MutexFactoryInterface $mutexFactory = null)
    {
        $this->mutexFactory = $mutexFactory;
    }

    /**
     * @param TransitionSelectorInterface $transitionSelector
     */
    public function setTransitonSelector(TransitionSelectorInterface $transitionSelector)
    {
        $this->transitonSelector = $transitionSelector;
    }

    /**
     * @param \SplObserver $observer
     */
    public function attachStatemachineObserver(\SplObserver $observer)
    {
        $this->statemachineObserver->attach($observer);
    }

    /**
     * @param \SplObserver $observer
     */
    public function detachStatemachineObserver(\SplObserver $observer)
    {
        $this->statemachineObserver->detach($observer);
    }

    /**
     * @return \Traversable
     */
    public function getStatemachineObserver()
    {
        return $this->statemachineObserver;
    }

    /**
     * @param object $subject
     *
     * @return \MetaborStd\Statemachine\StatemachineInterface
     */
    public function createStatemachine($subject)
    {
        $process = $this->processDetector->detectProcess($subject);
        if ($this->stateNameDetector) {
            $stateName = $this->stateNameDetector->detectCurrentStateName($subject);
        } else {
            $stateName = null;
        }
        if ($this->mutexFactory) {
            $mutex = $this->mutexFactory->createMutex($subject);
        } else {
            $mutex = null;
        }

        $statemachine = new Statemachine($subject, $process, $stateName, $this->transitonSelector, $mutex);

        foreach ($this->statemachineObserver as $observer) {
            $statemachine->attach($observer);
        }

        return $statemachine;
    }
}
