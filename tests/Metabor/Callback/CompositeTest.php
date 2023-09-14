<?php

namespace Metabor\Callback;

use PHPUnit\Framework\MockObject\Invocation;
use PHPUnit\Framework\MockObject\Rule\InvocationOrder;

/**
 * @author Oliver Tischlinger
 */
class CompositeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param Invocation $matcher
     * @param mixed                                            $parameter
     *
     * @return \MetaborStd\CallbackInterface
     */
    protected function createCallbackMock(InvocationOrder $matcher, $parameter = null)
    {
        $mock = $this->getMockForAbstractClass('\MetaborStd\CallbackInterface');
        $method = $mock->expects($matcher)->method('__invoke');
        if ($parameter) {
            $method->with($parameter);
        } else {
            $method->withAnyParameters();
        }

        return $mock;
    }

    /**
     *
     */
    public function testCallsAllAttachedCallbacksWhenInvoked()
    {
        $composite = new Composite();
        $parameter = new \stdClass();
        $callbackAttached1 = $this->createCallbackMock($this->once(), $parameter);
        $composite->attach($callbackAttached1);
        $callbackNotAttached1 = $this->createCallbackMock($this->never());
        $composite->attach($callbackNotAttached1);

        $callbackAttached2 = $this->createCallbackMock($this->once(), $parameter);
        $composite->attach($callbackAttached2);
        $callbackNotAttached2 = $this->createCallbackMock($this->never());
        $composite->attach($callbackNotAttached2);

        $composite->detach($callbackNotAttached1);
        $composite->detach($callbackNotAttached2);

        $composite($parameter);
    }
}
