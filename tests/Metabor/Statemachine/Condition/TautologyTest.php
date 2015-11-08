<?php

namespace Metabor\Statemachine\Condition;

use MetaborStd\NamedInterfaceTest;

/**
 * @author Oliver Tischlinger
 */
class TautologyTest extends NamedInterfaceTest
{
    /**
     * @see \MetaborStd\NamedInterfaceTest::createTestInstance()
     */
    protected function createTestInstance()
    {
        return new Tautology('TestCondition');
    }

    /**
     *
     */
    public function testIsAllwaysTrue()
    {
        $instance = $this->createTestInstance();
        $subject = new \stdClass();
        $context = new \ArrayIterator();
        $result = $instance->checkCondition($subject, $context);
        $this->assertTrue($result);
    }
}
