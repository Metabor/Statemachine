<?php

namespace Metabor\Statemachine\Factory;

use Metabor\Statemachine\Condition\Tautology;
use Metabor\Statemachine\Factory\TransitionSelector\ScoreTransition;
use Metabor\Statemachine\State;
use Metabor\Statemachine\Transition;

/**
 * @author Oliver Tischlinger
 */
class ScoreTransitionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return \Metabor\Statemachine\Transition[]
     */
    public function testIfThereIsOnlyOneTransitionWithoutEventAndTransitionUseThis()
    {
        $targetState = new State('TargetState');

        $transitions = array();
        $transitionAlwaysActive = new Transition($targetState);
        $transitions[] = $transitionAlwaysActive;

        array_rand($transitions);

        $selector = new ScoreTransition();
        $result = $selector->selectTransition(new \ArrayIterator($transitions));

        $this->assertEquals($transitionAlwaysActive, $result);

        return $transitions;
    }

    /**
     * @depends testIfThereIsOnlyOneTransitionWithoutEventAndTransitionUseThis
     *
     * @param array $transitions
     *
     * @return \Metabor\Statemachine\Transition[]
     */
    public function testPreferTransitionWithCondition(array $transitions)
    {
        $targetState = new State('TargetState');
        $condition = new Tautology('Always True');

        $transitionWithCondition = new Transition($targetState, null, $condition);
        $transitions[] = $transitionWithCondition;

        array_rand($transitions);

        $selector = new ScoreTransition();
        $result = $selector->selectTransition(new \ArrayIterator($transitions));

        $this->assertEquals($transitionWithCondition, $result);

        return $transitions;
    }

    /**
     * @depends testIfThereIsOnlyOneTransitionWithoutEventAndTransitionUseThis
     *
     * @param array $transitions
     *
     * @return \Metabor\Statemachine\Transition[]
     */
    public function testPreferTransitionWithEvent(array $transitions)
    {
        $targetState = new State('TargetState');
        $eventName = 'testEvent';

        $transitionWithEvent = new Transition($targetState, $eventName);
        $transitions[] = $transitionWithEvent;

        array_rand($transitions);

        $selector = new ScoreTransition();
        $result = $selector->selectTransition(new \ArrayIterator($transitions));

        $this->assertEquals($transitionWithEvent, $result);

        return $transitions;
    }

    /**
     * @depends testPreferTransitionWithCondition
     *
     * @param array $transitions
     */
    public function testPrefereTransitionWithEventAndCondition(array $transitions)
    {
        $targetState = new State('TargetState');
        $eventName = 'testEvent';
        $condition = new Tautology('Always True');

        $transitionWithEventAndCondition = new Transition($targetState, $eventName, $condition);
        $transitions[] = $transitionWithEventAndCondition;

        array_rand($transitions);

        $selector = new ScoreTransition();
        $result = $selector->selectTransition(new \ArrayIterator($transitions));

        $this->assertEquals($transitionWithEventAndCondition, $result);
    }

    /**
     *
     */
    public function testThrowsAnExceptionIfMoreThanOneTransitionIsInTheHighestLevel()
    {
        $targetState = new State('TargetState');

        $transitions = array();
        $transitions[] = new Transition($targetState);
        $transitions[] = new Transition($targetState);

        $selector = new ScoreTransition();

        $this->expectException('\RuntimeException');
        $selector->selectTransition(new \ArrayIterator($transitions));
    }
}
