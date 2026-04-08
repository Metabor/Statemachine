<?php

namespace Metabor\Statemachine\Factory;

use MetaborStd\Statemachine\Factory\ProcessDetectorInterface;
use MetaborStd\Statemachine\ProcessInterface;

/**
 * @author Oliver Tischlinger
 */
class SingleProcessDetector implements ProcessDetectorInterface
{
    private ProcessInterface $process;

    public function __construct(ProcessInterface $process)
    {
        $this->process = $process;
    }

    public function detectProcess(object $subject): ProcessInterface
    {
        return $this->process;
    }
}
