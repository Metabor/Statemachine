<?php

namespace Metabor\Statemachine\Factory;

use Metabor\Statemachine\Condition\Tautology;
use Metabor\Statemachine\Factory\TransitionSelector\ScoreTransition;
use Metabor\Statemachine\State;
use Metabor\Statemachine\Transition;
use PHPUnit\Framework\Attributes\Depends;

/**
 * @author Oliver Tischlinger
 */
class ScoreTransitionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return \Metabor\Statemachine\Transition[]
     */
    public function testIfThereIsOnlyOneTransitionWithoutEventAndTransitionUseThis(): array
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

    #[Depends('testIfThereIsOnlyOneTransitionWithoutEventAndTransitionUseThis')]
    public function testPreferTransitionWithCondition(array $transitions): array
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

    #[Depends('testIfThereIsOnlyOneTransitionWithoutEventAndTransitionUseThis')]
    public function testPreferTransitionWithEvent(array $transitions): array
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

    #[Depends('testPreferTransitionWithCondition')]
    public function testPrefereTransitionWithEventAndCondition(array $transitions): void
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
    public function testThrowsAnExceptionIfMoreThanOneTransitionIsInTheHighestLevel(): void
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
