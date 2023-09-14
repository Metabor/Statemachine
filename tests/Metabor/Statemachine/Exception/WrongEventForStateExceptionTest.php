<?php

namespace Metabor\Statemachine\Exception;

class WrongEventForStateExceptionTest extends \PHPUnit\Framework\TestCase
{
    public function testStateNameIsAccessible()
    {
        $exception = new WrongEventForStateException('stateName', 'eventName', 0);

        $this->assertSame('stateName', $exception->getStateName());
    }

    public function testEventNameIsAccessible()
    {
        $exception = new WrongEventForStateException('stateName', 'eventName', 0);

        $this->assertSame('eventName', $exception->getEventName());
    }
}
