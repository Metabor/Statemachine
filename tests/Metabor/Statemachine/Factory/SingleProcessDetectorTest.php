<?php

namespace Metabor\Statemachine\Factory;

use Metabor\Statemachine\Process;
use Metabor\Statemachine\State;

/**
 * @author Oliver Tischlinger
 */
class SingleProcessDetectorTest extends \PHPUnit\Framework\TestCase
{
    public function testWillAlwaysReturnTheSameProcess()
    {
        $process = new Process('test', new State('new'));

        $detector = new SingleProcessDetector($process);
        $subject = new \stdClass();
        $result = $detector->detectProcess($subject);

        $this->assertSame($process, $result);
    }
}
