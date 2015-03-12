<?php
namespace Metabor\Statemachine\Condition;

use MetaborStd\NamedInterfaceTest;

/**
 * @author Oliver Tischlinger
 */
class ContradictionTest extends NamedInterfaceTest
{
    /**
     * @see \MetaborStd\NamedInterfaceTest::createTestInstance()
     */
    protected function createTestInstance()
    {
        return new Contradiction('TestCondition');
    }

    /**
     *
     */
    public function testIsAllwaysFalse()
    {
        $instance = $this->createTestInstance();
        $subject = new \stdClass();
        $context = new \ArrayIterator();
        $result = $instance->checkCondition($subject, $context);
        $this->assertFalse($result);
    }
}
