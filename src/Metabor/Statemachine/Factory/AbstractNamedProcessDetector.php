<?php

namespace Metabor\Statemachine\Factory;

use Metabor\NamedCollection;
use MetaborStd\Statemachine\Factory\ProcessDetectorInterface;
use MetaborStd\Statemachine\ProcessInterface;

/**
 * @author Oliver Tischlinger
 */
abstract class AbstractNamedProcessDetector implements ProcessDetectorInterface
{
    private NamedCollection $processes;

    public function __construct()
    {
        $this->processes = new NamedCollection();
    }

    public function addProcess(ProcessInterface $process): void
    {
        $this->processes->add($process);
    }

    abstract protected function detectProcessName(object $subject): string;

    public function detectProcess(object $subject): ProcessInterface
    {
        $name = $this->detectProcessName($subject);
        return $this->processes->get($name);
    }

    public function getProcesses(): \Traversable
    {
        return $this->processes;
    }
}
