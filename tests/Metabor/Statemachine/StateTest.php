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

    /**
     * 
     */
    public function testUsesMetadataForFlags()
    {
        $offset = 'TestOffset';
        $value = 'TestValue';
        $instance = $this->createTestInstance();
        $this->assertArrayNotHasKey($offset, $instance);
        $instance[$offset] = $value;
        $this->assertArrayHasKey($offset, $instance);
        $this->assertEquals($value, $instance[$offset]);
        unset($instance[$offset]);
        $this->assertArrayNotHasKey($offset, $instance);
    }
}
