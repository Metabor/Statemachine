<?php
namespace Metabor\Statemachine\Factory;

use Metabor\Statemachine\Process;
use Metabor\Statemachine\State;
use MetaborStd\Statemachine\Factory\FactoryInterfaceTest;

/**
 * 
 * @author Oliver Tischlinger
 *
 */
class FactoryTest extends FactoryInterfaceTest
{
    /**
     * @see \MetaborStd\Statemachine\Factory\FactoryInterfaceTest::createTestInstance()
     */
    protected function createTestInstance()
    {
        $processDetector = $this->getMockForAbstractClass('\MetaborStd\Statemachine\Factory\ProcessDetectorInterface');
        $name = 'TestProcess';
        $initialState = new State('TestState');
        $process = new Process($name, $initialState);
        $processDetector->expects($this->atLeastOnce())->method('detectProcess')->willReturn($process);
        $factory = new Factory($processDetector);
        return $factory;
    }

    /**
     * @see \MetaborStd\Statemachine\Factory\FactoryInterfaceTest::getSubject()
     */
    protected function getSubject()
    {
        return new \stdClass();
    }
}
