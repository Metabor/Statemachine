<?php

namespace Metabor\Statemachine\Filter;

use Metabor\Statemachine\Condition\Tautology;
use Metabor\Statemachine\StateCollection;
use Metabor\Statemachine\Util\SetupHelper;

/**
 * @author otischlinger
 */
class FilterStateByTransitionTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     */
    public function testFiltersStatesThatHaveTransitionsWithoutAnEvent()
    {
        $eventName = 'event';
        $stateCollection = new StateCollection();
        $helper = new SetupHelper($stateCollection);
        $helper->findOrCreateTransition('foo', 'bar', $eventName);
        $helper->findOrCreateTransition('bar', 'baz', null, new Tautology('condition'));

        $filter = new FilterStateByTransition($stateCollection->getStates());
        $filteredStates = iterator_to_array($filter);

        $this->assertNotContains($stateCollection->getState('foo'), $filteredStates);
        $this->assertContains($stateCollection->getState('bar'), $filteredStates);
        $this->assertNotContains($stateCollection->getState('baz'), $filteredStates);
    }
}
