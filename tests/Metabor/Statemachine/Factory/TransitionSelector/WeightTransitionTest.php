<?php

namespace Metabor\Statemachine\Factory;

use Metabor\Statemachine\Factory\TransitionSelector\WeightTransition;
use Metabor\Statemachine\State;
use Metabor\Statemachine\Transition;

/**
 * @author Oliver Tischlinger
 */
class WeightTransitionTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     */
    public function testIfThereIsOnlyOneTransitionWithoutEventAndTransitionUseThis()
    {
        $targetState = new State('TargetState');

        $transitions = array();
        $transitionAlwaysActive1 = new Transition($targetState);
        $transitionAlwaysActive1->setWeight(0.001);
        $transitions[] = $transitionAlwaysActive1;

        $transitionAlwaysActive2 = new Transition($targetState);
        $transitionAlwaysActive2->setWeight(0.002);
        $transitions[] = $transitionAlwaysActive2;

        array_rand($transitions);

        $selector = new WeightTransition();
        $result = $selector->selectTransition(new \ArrayIterator($transitions));

        $this->assertEquals($transitionAlwaysActive2, $result);
    }

    /**
     *
     */
    public function testThrowsAnExceptionIfMoreThanOneTransitionHasHighestWeight()
    {
        $targetState = new State('TargetState');

        $transitions = array();
        $transition = new Transition($targetState);
        $transition->setWeight(0.001);
        $transitions[] = $transition;
        $transition = new Transition($targetState);
        $transition->setWeight(0.001);
        $transitions[] = $transition;

        $selector = new WeightTransition();

        $this->expectException('\RuntimeException');
        $selector->selectTransition(new \ArrayIterator($transitions));
    }
}
