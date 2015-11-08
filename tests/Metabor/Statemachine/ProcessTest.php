<?php

namespace Metabor\Statemachine;

use MetaborStd\Statemachine\ProcessInterfaceTest;

/**
 * @author Oliver Tischlinger
 */
class ProcessTest extends ProcessInterfaceTest
{
    /**
     * @see \MetaborStd\NamedInterfaceTest::createTestInstance()
     */
    protected function createTestInstance()
    {
        $name = $this->getOneStateNameOfTheCollection();
        $state = new State($name);

        return new Process('TestProcess', $state);
    }

    /**
     * @see \MetaborStd\Statemachine\StateCollectionInterfaceTest::getOneStateNameOfTheCollection()
     */
    protected function getOneStateNameOfTheCollection()
    {
        return 'TestState';
    }
}
