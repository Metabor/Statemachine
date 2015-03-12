<?php
namespace Metabor\Statemachine\Condition;

use MetaborStd\NamedInterfaceTest;

/**
 * @author Oliver Tischlinger
 */
class CallbackTest extends NamedInterfaceTest
{
    /**
     * @var boolean
     */
    private $wasCalled = false;

    /**
     * @see \MetaborStd\NamedInterfaceTest::createTestInstance()
     */
    protected function createTestInstance()
    {
        return new Callback('TestCondition', $this);
    }

    /**
     *
     */
    public function testConvertsCallableToCondition()
    {
        $instance = $this->createTestInstance();
        $this->wasCalled = false;
        $subject = new \stdClass();
        $context = new \ArrayIterator();
        $result = $instance->checkCondition($subject, $context);
        $this->assertTrue($this->wasCalled);
        $this->assertTrue($result);
    }

    /**
     * @return boolean
     */
    public function __invoke()
    {
        $this->wasCalled = true;

        return true;
    }
}
