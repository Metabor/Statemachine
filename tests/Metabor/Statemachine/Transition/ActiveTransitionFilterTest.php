<?php

namespace Metabor\Statemachine\Transition;

use MetaborStd\Event\EventInterface;

/**
 * ActiveTransitionFilter test case.
 */
class ActiveTransitionFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param bool           $active
     * @param object         $subject
     * @param \ArrayAccess   $context
     * @param EventInterface $event
     *
     * @return \MetaborStd\Statemachine\TransitionInterface
     */
    protected function createTransition($active, $subject, \ArrayAccess $context, EventInterface $event = null)
    {
        $transition = $this->getMockForAbstractClass('\MetaborStd\Statemachine\TransitionInterface');
        $transition->expects($this->atLeastOnce())->method('isActive')->with($subject, $context, $event)
                ->willReturn($active);

        return $transition;
    }

    /**
     *
     */
    public function testFiltersAllNotActiveTransitions()
    {
        $subject = new \stdClass();
        $context = new \ArrayObject();
        $event = null;

        $transitions = new \SplObjectStorage();
        $transitionActive = $this->createTransition(true, $subject, $context, $event);
        $transitions->attach($transitionActive);
        $transitionNotActive = $this->createTransition(false, $subject, $context, $event);
        $transitions->attach($transitionNotActive);

        $filteredTransitions = new ActiveTransitionFilter($transitions, $subject, $context, $event);
        $this->assertContains($transitionActive, $filteredTransitions);
        $this->assertNotContains($transitionNotActive, $filteredTransitions);
    }
}
