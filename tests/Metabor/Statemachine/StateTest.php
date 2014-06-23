<?php
namespace Metabor\Statemachine;
use MetaborStd\Statemachine\StateInterfaceTest;

/**
 * 
 * @author Oliver Tischlinger
 *
 */
class StateTest extends StateInterfaceTest
{
    /**
     * @see \MetaborStd\NamedInterfaceTest::createTestInstance()
     */
    protected function createTestInstance()
    {
        return new State('TestState');
    }
}
