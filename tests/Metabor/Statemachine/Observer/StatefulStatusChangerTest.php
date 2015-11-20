<?php

namespace Metabor\Statemachine\Observer;

use Metabor\Statemachine\Process;
use Metabor\Statemachine\State;
use Metabor\Statemachine\Statemachine;
use MetaborStd\Statemachine\StatefulInterface;

/**
 * @author otischlinger
 */
class StatefulStatusChangerTest extends \PHPUnit_Framework_TestCase implements StatefulInterface
{
    /**
     * @var string
     */
    private $currentStateName;

    /**
     * @return string
     */
    public function getCurrentStateName()
    {
        return $this->currentStateName;
    }

    /**
     * @param string $currentStateName
     */
    public function setCurrentStateName($currentStateName)
    {
        $this->currentStateName = $currentStateName;
    }

    /**
     *
     */
    public function testChangesStatusOnStatefulObjects()
    {
        $stateName = 'stateName';
        $state = new State($stateName);
        $process = new Process('process', $state);
        $stateMachine = new Statemachine($this, $process);

        $observer = new StatefulStatusChanger();
        $observer->update($stateMachine);
        $this->assertEquals($stateName, $this->currentStateName);
    }
}
