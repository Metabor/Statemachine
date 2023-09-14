<?php

namespace Metabor;

use MetaborStd\NamedInterfaceTest;

/**
 * @author Oliver Tischlinger
 */
class NamedCollectionTest extends \PHPUnit\Framework\TestCase
{
    public function testStoresNamedObject()
    {
        $objectName = 'TestName';
        $named1 =  new Named($objectName);

        $collection = new NamedCollection();
        $collection->add($named1);
        $this->assertSame($named1, $collection->get($objectName));
    }
}
