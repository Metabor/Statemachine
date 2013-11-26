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
     * @param ProcessDetectorInterface $processDetector
     * @param StateNameDetectorInterface $stateNameDetector
     */
    public function __construct(ProcessDetectorInterface $processDetector,
            StateNameDetectorInterface $stateNameDetector = null)
    {
        $this->processDetector = $processDetector;
        $this->stateNameDetector = $stateNameDetector;
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
            return new Statemachine($subject, $process, $stateName);
        } else {
            return new Statemachine($subject, $process);
        }
    }
}
