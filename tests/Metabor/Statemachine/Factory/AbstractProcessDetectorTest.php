<?php

namespace Metabor\Statemachine\Factory;

use Metabor\Statemachine\Process;
use Metabor\Statemachine\State;

/**
 * @author Oliver Tischlinger
 */
class AbstractProcessDetectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param \stdClass $subject
     * @return string
     */
    public function detectProcessName(\stdClass $subject)
    {
        return $subject->process;
    }

    /**
     *
     */
    public function testWillAlwaysReturnTheSameProcess()
    {
        $processA = new Process('A', new State('new'));
        $processB = new Process('B', new State('new'));

        $detector = $this->getMockForAbstractClass('Metabor\Statemachine\Factory\AbstractNamedProcessDetector');
        $detector->expects($this->atLeastOnce())->method('detectProcessName')
            ->willReturnCallback(array($this, 'detectProcessName'));
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
