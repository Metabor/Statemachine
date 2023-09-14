<?php

namespace Metabor\Statemachine\Observer;

use Metabor\Named;
use Metabor\Statemachine\Process;
use Metabor\Statemachine\State;
use Metabor\Statemachine\Statemachine;
use Psr\Log\LogLevel;

/**
 * @author otischlinger
 */
class TransitionLoggerTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     */
    public function testChangesStatusOnStatefulObjects()
    {
        $subject = new Named('SubjectName');
        $state = new State('stateName');
        $process = new Process('process', $state);
        $stateMachine = new Statemachine($subject, $process);

        $message = 'Transition for "SubjectName" to "stateName"';
        $context = array();
        $context['subject'] = $subject;
        $context['currentState'] = $state;
        $context['lastState'] = null;
        $context['transition'] = null;

        $logger = $this->getMockForAbstractClass('Psr\Log\LoggerInterface');
        $logger->expects($this->once())->method('log')->with(LogLevel::INFO, $message, $context);
        $observer = new TransitionLogger($logger);
        $observer->update($stateMachine);
    }
}
