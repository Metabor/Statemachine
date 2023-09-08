<?php

namespace Metabor\Statemachine\Filter;

use Metabor\Statemachine\StateCollection;
use Metabor\Statemachine\Util\SetupHelper;

/**
 * @author otischlinger
 */
class FilterStateByEventTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     */
    public function testFiltersStatesThatHaveEvent()
    {
        $eventName = 'event';
        $stateCollection = new StateCollection();
        $helper = new SetupHelper($stateCollection);
        $helper->findOrCreateTransition('foo', 'bar', $eventName);

        $filter = new FilterStateByEvent($stateCollection->getStates(), $eventName);
        $filteredStates = iterator_to_array($filter);

        $this->assertContains($stateCollection->getState('foo'), $filteredStates);
        $this->assertNotContains($stateCollection->getState('bar'), $filteredStates);
    }
}
