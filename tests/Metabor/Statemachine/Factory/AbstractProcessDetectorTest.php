<?php

namespace Metabor\Statemachine\Factory;

use Metabor\Statemachine\Process;
use Metabor\Statemachine\State;

/**
 * @author Oliver Tischlinger
 */
class AbstractProcessDetectorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param \stdClass $subject
     * @return string
     */
    public function detectProcessName(\stdClass $subject)
    {
        return $subject->process;
    }

    public function testWillAlwaysReturnTheSameProcess(): void
    {
        $processA = new Process('A', new State('new'));
        $processB = new Process('B', new State('new'));

        $detector = new class extends \Metabor\Statemachine\Factory\AbstractNamedProcessDetector {
            protected function detectProcessName(object $subject): string
            {
                return $subject->process;
            }
        };
        $detector->addProcess($processA);
        $detector->addProcess($processB);

        $subject = new \stdClass();
        $subject->process = 'A';
        $result = $detector->detectProcess($subject);
        $this->assertSame($processA, $result);

        $subject = new \stdClass();
        $subject->process = 'B';
        $result = $detector->detectProcess($subject);
        $this->assertSame($processB, $result);
    }
}
