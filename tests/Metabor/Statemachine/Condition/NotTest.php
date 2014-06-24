<?php
namespace Metabor\Statemachine\Condition;

use MetaborStd\NamedInterfaceTest;

/**
 * 
 * @author Oliver Tischlinger
 *
 */
class NotTest extends NamedInterfaceTest
{

    /**
     * 
     * @see \MetaborStd\NamedInterfaceTest::createTestInstance()
     */
    protected function createTestInstance()
    {
        return new Not(new Tautology('TestCondition'));
    }

    /**
     * 
     */
    public function testInversedTheInnerCondition()
    {
        $instance = $this->createTestInstance();
        $subject = new \stdClass();
        $context = new \ArrayIterator();
        $result = $instance->checkCondition($subject, $context);
        $this->assertFalse($result);
    }
}
