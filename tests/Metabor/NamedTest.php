<?php

namespace Metabor;

use MetaborStd\NamedInterfaceTest;

/**
 * @author Oliver Tischlinger
 */
class NamedTest extends NamedInterfaceTest
{
    /**
     * @see \MetaborStd\NamedInterfaceTest::createTestInstance()
     */
    protected function createTestInstance()
    {
        return new Named('TestName');
    }
}
