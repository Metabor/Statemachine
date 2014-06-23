<?php
namespace Metabor\Event;
use MetaborStd\Event\EventInterfaceTest;

/**
 * 
 * @author Oliver Tischlinger
 *
 */
class EventTest extends EventInterfaceTest
{
    /**
     * @see \MetaborStd\NamedInterfaceTest::createTestInstance()
     */
    protected function createTestInstance()
    {
        return new Event('TestEvent');
    }
}
