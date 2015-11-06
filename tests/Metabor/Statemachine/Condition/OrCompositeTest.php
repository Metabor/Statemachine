<?php
namespace Metabor\Statemachine\Condition;

use MetaborStd\NamedInterfaceTest;

/**
 * @author Oliver Tischlinger
 */
class OrCompositeTest extends NamedInterfaceTest
{
    /**
     * @see \MetaborStd\NamedInterfaceTest::createTestInstance()
     */
    protected function createTestInstance()
    {
        return new OrComposite(new Contradiction('TestCondition'));
    }

    /**
     *
     */
    public function testCombinesTheInnerConditionWithOr()
    {
        $instance = $this->createTestInstance();
        $subject = new \stdClass();
        $context = new \ArrayIterator();
        $result = $instance->checkCondition($subject, $context);
        $this->assertFalse($result);
        $instance->addOr(new Tautology('Other Condition'));
        $result = $instance->checkCondition($subject, $context);
        $this->assertTrue($result);
    }
}
