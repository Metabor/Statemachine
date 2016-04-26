<?php

namespace Metabor\Statemachine\Factory;

use Metabor\NamedCollection;
use MetaborStd\Statemachine\Factory\ProcessDetectorInterface;
use MetaborStd\Statemachine\ProcessInterface;

/**
 * @author otischlinger
 */
abstract class AbstractNamedProcessDetector implements ProcessDetectorInterface
{
    /**
     * @var NamedCollection
     */
    private $processes;

    /**
     * AbstractNamedProcessDetector constructor.
     */
    public function __construct()
    {
        $this->processes = new NamedCollection();
    }

    /**
     * @param object $subject
     * @return string
     */
    abstract protected function detectProcessName($subject);

    /**
     * @param ProcessInterface $process
     */
    public function addProcess(ProcessInterface $process)
    {
        $this->processes->add($process);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasProcess($name)
    {
        return $this->processes->has($name);
    }

    /**
     * @param object $subject
     * @return ProcessInterface
     */
    public function detectProcess($subject)
    {
        $name = $this->detectProcessName($subject);

        return $this->processes->get($name);
    }
}
