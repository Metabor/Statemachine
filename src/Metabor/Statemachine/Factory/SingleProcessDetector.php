<?php

namespace Metabor\Statemachine\Factory;

use MetaborStd\Statemachine\Factory\ProcessDetectorInterface;
use MetaborStd\Statemachine\ProcessInterface;

/**
 * @author otischlinger
 */
class SingleProcessDetector implements ProcessDetectorInterface
{
    /**
     * @var ProcessInterface
     */
    private $process;

    /**
     * SingleProcessDetector constructor.
     * @param ProcessInterface $process
     */
    public function __construct(ProcessInterface $process)
    {
        $this->process = $process;
    }

    /**
     * @param object $subject
     * @return ProcessInterface
     */
    public function detectProcess($subject)
    {
        return $this->process;
    }
}
