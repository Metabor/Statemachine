<?php

namespace Metabor\Statemachine\Observer;

use Metabor\Statemachine\Process;
use Metabor\Statemachine\State;
use Metabor\Statemachine\Statemachine;
use MetaborStd\Statemachine\StatefulInterface;

/**
 * @author otischlinger
 */
class StatefulStatusChangerTest extends \PHPUnit\Framework\TestCase implements StatefulInterface
{
    /**
     * @var string
     */
    private string $currentStateName;

    /**
     * @return string
     */
    public function getCurrentStateName(): string
    {
        return $this->currentStateName;
    }

    /**
     * @param string $currentStateName
     */
    public function setCurrentStateName($currentStateName): void
    {
        $this->currentStateName = $currentStateName;
    }

    /**
     *
     */
    public function testChangesStatusOnStatefulObjects(): void
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
