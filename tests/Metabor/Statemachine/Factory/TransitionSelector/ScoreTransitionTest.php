<?php
namespace Metabor\Statemachine\Factory;

use Metabor\Statemachine\Condition\Tautology;
use Metabor\Statemachine\Factory\TransitionSelector\ScoreTransition;
use Metabor\Statemachine\State;
use Metabor\Statemachine\Transition;

/**
 * @author Oliver Tischlinger
 */
class ScoreTransitionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \Metabor\Statemachine\Transition[]
     */
    public function testIfThereIsOnlyOneTransitionWithoutEventAndTransitionUseThis()
    {
        $targetState = new State('TargetState');

        $transitions = array();
        $transitionAllwaysActive = new Transition($targetState);
        $transitions[] = $transitionAllwaysActive;

        array_rand($transitions);

        $selector = new ScoreTransition();
        $result = $selector->selectTransition(new \ArrayIterator($transitions));

        $this->assertEquals($transitionAllwaysActive, $result);

        return $transitions;
    }

    /**
     * @depends testIfThereIsOnlyOneTransitionWithoutEventAndTransitionUseThis
     *
     * @param array $transitions
     *
     * @return \Metabor\Statemachine\Transition
     */
    public function testPrefereTransitionWithCondition(array $transitions)
    {
        $targetState = new State('TargetState');
        $condition = new Tautology('Allways True');

        $transitionWithConditon = new Transition($targetState, null, $condition);
        $transitions[] = $transitionWithConditon;

        array_rand($transitions);

        $selector = new ScoreTransition();
        $result = $selector->selectTransition(new \ArrayIterator($transitions));

        $this->assertEquals($transitionWithConditon, $result);

        return $transitions;
    }

    /**
     * @depends testIfThereIsOnlyOneTransitionWithoutEventAndTransitionUseThis
     *
     * @param array $transitions
     *
     * @return \Metabor\Statemachine\Transition
     */
    public function testPrefereTransitionWithEvent(array $transitions)
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
     * @depends testPrefereTransitionWithEvent
     *
     * @param array $transitions
     *
     * @return \Metabor\Statemachine\Transition
     */
    public function testPrefereTransitionWithEventAndCondition(array $transitions)
    {
        $targetState = new State('TargetState');
        $eventName = 'testEvent';
        $condition = new Tautology('Allways True');

        $transitionWithEventAndConditon = new Transition($targetState, $eventName, $condition);
        $transitions[] = $transitionWithEventAndConditon;

        array_rand($transitions);

        $selector = new ScoreTransition();
        $result = $selector->selectTransition(new \ArrayIterator($transitions));

        $this->assertEquals($transitionWithEventAndConditon, $result);
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

        $this->setExpectedException('\RuntimeException');
        $selector->selectTransition(new \ArrayIterator($transitions));
    }
}
