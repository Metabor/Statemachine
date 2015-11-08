<?php

namespace Metabor\Statemachine;

use MetaborStd\Statemachine\StateCollectionInterfaceTest;

/**
 * @author Oliver Tischlinger
 */
class StateCollectionTest extends StateCollectionInterfaceTest
{
    /**
     * @see \MetaborStd\NamedInterfaceTest::createTestInstance()
     */
    protected function createTestInstance()
    {
        $name = $this->getOneStateNameOfTheCollection();
        $state = new State($name);
        $instance = new StateCollection();
        $instance->addState($state);

        return $instance;
    }

    /**
     * @see \MetaborStd\Statemachine\StateCollectionInterfaceTest::getOneStateNameOfTheCollection()
     */
    protected function getOneStateNameOfTheCollection()
    {
        return 'TestState';
    }
}
