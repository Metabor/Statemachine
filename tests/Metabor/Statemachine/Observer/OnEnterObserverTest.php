<?php

namespace Metabor\Statemachine\Observer;

use Metabor\Statemachine\Process;
use Metabor\Statemachine\State;
use Metabor\Statemachine\StateCollection;
use Metabor\Statemachine\Statemachine;
use Metabor\Statemachine\Util\SetupHelper;

/**
 * @author otischlinger
 */
class OnEnterObserverTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testTriggerEventIfStatusIsChangedAndNewStateHasRegisteredEvent()
    {
        $process = new Process('process_name', new State('initinal'));
        $collection = new StateCollection();
        $helper = new SetupHelper($collection);
        $helper->findOrCreateTransition('initinal', 'second', 'go');
        $helper->findOrCreateTransition('second', 'error', 'error');
        $eventName = 'eventName';
        $helper->findOrCreateTransition('second', 'final', $eventName);
        $process->merge($collection);

        $subject = new \stdClass();
        $statemachine = new Statemachine($subject, $process);
        $statemachine->attach(new OnEnterObserver($eventName));
        $statemachine->triggerEvent('go');

        $this->assertEquals($process->getState('final'), $statemachine->getCurrentState());
    }
}
