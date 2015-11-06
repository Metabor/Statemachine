<?php
namespace Metabor\Statemachine\Condition;

use MetaborStd\NamedInterfaceTest;

/**
 * @author Oliver Tischlinger
 */
class AndCompositeTest extends NamedInterfaceTest
{
    /**
     * @see \MetaborStd\NamedInterfaceTest::createTestInstance()
     */
    protected function createTestInstance()
    {
        return new AndComposite(new Tautology('TestCondition'));
    }

    /**
     *
     */
    public function testCombinesTheInnerConditionWithAnd()
    {
        $instance = $this->createTestInstance();
        $subject = new \stdClass();
        $context = new \ArrayIterator();
        $result = $instance->checkCondition($subject, $context);
        $this->assertTrue($result);
        $instance->addAnd(new Contradiction('Other Condition'));
        $result = $instance->checkCondition($subject, $context);
        $this->assertFalse($result);
    }
}
