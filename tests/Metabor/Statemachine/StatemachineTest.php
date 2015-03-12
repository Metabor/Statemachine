<?php
namespace Metabor\Statemachine;

use Metabor\Statemachine\Condition\SymfonyExpression;
use MetaborStd\Statemachine\StatemachineInterfaceTest;

/**
 * @author Oliver Tischlinger
 */
class StatemachineTest extends StatemachineInterfaceTest
{
    const TEST_EVENT = 'test event';
    const SECOND_STATE = 'second';
    const END_STATE = 'end';

    /**
     * @see \MetaborStd\Statemachine\StatemachineInterfaceTest::createTestInstance()
     */
    protected function createTestInstance()
    {
        $subject = new \stdClass();
        $subject->canBeClosed = false;

        $initialState = new State('new');
        $transition = $this->getTransitionForTriggerTest();
        $initialState->addTransition($transition);
        $transition->getTargetState()->addTransition($this->getTransitionForCheckTest());

        $process = new Process('testProcess', $initialState);

        return new Statemachine($subject, $process);
    }

    /**
     * @see \MetaborStd\Statemachine\StatemachineInterfaceTest::getTransitionForTriggerTest()
     */
    protected function getTransitionForTriggerTest()
    {
        $secondState = new State(self::SECOND_STATE);

        return new Transition($secondState, self::TEST_EVENT);
    }

    /**
     * @return \MetaborStd\Statemachine\StatemachineInterface
     */
    protected function getTestInstanceForCheckTest()
    {
        $instance = parent::getTestInstanceForCheckTest();
        $instance->triggerEvent(self::TEST_EVENT);
        $instance->getSubject()->canBeClosed = true;

        return $instance;
    }

    /**
     * @see \MetaborStd\Statemachine\StatemachineInterfaceTest::getTransitionForCheckTest()
     */
    protected function getTransitionForCheckTest()
    {
        $endState = new State(self::END_STATE);
        $condition = new SymfonyExpression('subject.canBeClosed');

        return new Transition($endState, null, $condition);
    }
}
