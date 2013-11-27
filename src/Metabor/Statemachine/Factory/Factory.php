<?php
namespace Metabor\Statemachine\Factory;

use Metabor\Statemachine\Statemachine;
use MetaborStd\Statemachine\Factory\FactoryInterface;
use MetaborStd\Statemachine\Factory\StateNameDetectorInterface;
use MetaborStd\Statemachine\Factory\ProcessDetectorInterface;

/**
 *
 * @author Oliver Tischlinger
 *        
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
     * @var \SplObjectStorage
     */
    private $statemachineObserver;

    /**
     * @param ProcessDetectorInterface $processDetector
     * @param StateNameDetectorInterface $stateNameDetector
     */
    public function __construct(ProcessDetectorInterface $processDetector,
            StateNameDetectorInterface $stateNameDetector = null)
    {
        $this->processDetector = $processDetector;
        $this->stateNameDetector = $stateNameDetector;
        $this->statemachineObserver = new \SplObjectStorage();
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
     * 
     * @param object $subject
     * @return \MetaborStd\Statemachine\StatemachineInterface
     */
    public function createStatemachine($subject)
    {
        $process = $this->processDetector->detectProcess($subject);
        if ($this->stateNameDetector) {
            $stateName = $this->stateNameDetector->detectCurrentStateName($subject);
            $statemachine = new Statemachine($subject, $process, $stateName);
        } else {
            $statemachine = new Statemachine($subject, $process);
        }

        foreach ($this->statemachineObserver as $observer) {
            $statemachine->attach($observer);
        }

        return $statemachine;
    }
}
