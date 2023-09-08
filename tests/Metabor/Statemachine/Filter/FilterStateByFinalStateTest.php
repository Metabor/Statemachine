<?php

namespace Metabor\Statemachine\Filter;

use Metabor\Statemachine\StateCollection;
use Metabor\Statemachine\Util\SetupHelper;

/**
 * @author otischlinger
 */
class FilterStateByFinalStateTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     */
    public function testFiltersStatesThatHaveNoOutgoingTransitions()
    {
        $eventName = 'event';
        $stateCollection = new StateCollection();
        $helper = new SetupHelper($stateCollection);
        $helper->findOrCreateTransition('foo', 'bar', $eventName);

        $filter = new FilterStateByFinalState($stateCollection->getStates());
        $filteredStates = iterator_to_array($filter);

        $this->assertContains($stateCollection->getState('bar'), $filteredStates);
        $this->assertNotContains($stateCollection->getState('foo'), $filteredStates);
    }
}
